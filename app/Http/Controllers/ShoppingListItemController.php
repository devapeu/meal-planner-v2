<?php

namespace App\Http\Controllers;

use App\Models\ShoppingListItem;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppingListItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data, $request) {
            ShoppingListItem::where('user_id', $request->user()->id)
                ->increment('position');

            ShoppingListItem::create([
                'user_id'  => $request->user()->id,
                'item'     => $data['item'],
                'position' => 1,
            ]);
        });

        return back();
    }

    public function update(Request $request, ShoppingListItem $shoppingListItem): RedirectResponse
    {
        abort_unless($shoppingListItem->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'item' => ['required', 'string', 'max:255'],
        ]);

        $shoppingListItem->update($data);

        return back();
    }

    public function destroy(Request $request, ShoppingListItem $shoppingListItem) : RedirectResponse
    {
        abort_unless($shoppingListItem->user_id === $request->user()->id, 403);

        $shoppingListItem->delete();

        return back();
    }

    public function reorder (Request $request) : RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'distinct', 'exists:shopping_list_items,id'],
        ]);

        try {
            DB::transaction(function () use ($data, $request) {
                foreach ($data['items'] as $index => $item) {
                    ShoppingListItem::where('id', $item['id'])
                        ->where('user_id', $request->user()->id)
                        ->update(['position' => $index + 1]);
                }
            });
        } catch (Exception $error) {
            report($error);

            return back()->withErrors([
                'items' => 'Unable to reorder shopping list.',
            ]);
        }

        return back();
    }
}
