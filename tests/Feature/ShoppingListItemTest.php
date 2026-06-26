<?php

namespace Tests\Feature;

use App\Models\ShoppingListItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListItemTest extends TestCase {

    use RefreshDatabase;
    public function test_list_cannot_have_duplicate_ids(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('shopping-list.reorder'), [
            'items' => [
                ['id' => 1],
                ['id' => 1],
            ]
        ]);

        $response->assertSessionHasErrors(['items.1.id']);
    }

    public function test_user_cannot_reorder_other_users_items(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $item = ShoppingListItem::factory()
            ->for($other)
            ->create(['position' => 1]);

        $this->actingAs($user)
            ->post(route('shopping-list.reorder'), [
                'items' => [
                    ['id' => $item->id, ],
                ],
            ]);

        $this->assertEquals(1, $item->fresh()->position);
    }

    public function test_order_reorders_shopping_list_items(): void
    {
        $user = User::factory()->create();
        $list = ShoppingListItem::factory()
            ->for($user)
            ->count(3)
            ->sequence(
                ['position' => 1],
                ['position' => 2],
                ['position' => 3]
            )
            ->create();

        $this->actingAs($user)
            ->post(route('shopping-list.reorder'), [
                'items' => [
                    ['id' => 3 ],
                    ['id' => 2 ],
                    ['id' => 1 ],
                ]
            ]);

        $newList = ShoppingListItem::where('user_id', $user->id)
            ->orderBy('position')
            ->get();

        $this->assertEquals(3, $newList[0]->id);
        $this->assertEquals(2, $newList[1]->id);
        $this->assertEquals(1, $newList[2]->id);
    }
}
