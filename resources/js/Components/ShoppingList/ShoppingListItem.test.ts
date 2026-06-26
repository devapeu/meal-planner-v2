import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'
import ShoppingListItem from './ShoppingListItem.vue'

vi.mock('@inertiajs/vue3', () => ({
    router: {
        put: vi.fn(),
        delete: vi.fn(),
    },
}))

vi.mock('@iconoir/vue', () => ({
    EditPencil: { template: '<span class="icon-edit" />' },
    Trash: { template: '<span class="icon-trash" />' },
}))

import { router } from '@inertiajs/vue3'

const item = { id: 1, item: 'Milk', position: 0 }

function mountItem() {
    return mount(ShoppingListItem, { props: { item } })
}

beforeEach(() => {
    vi.clearAllMocks()
})

describe('view mode', () => {
    it('renders the item text', () => {
        const wrapper = mountItem()
        expect(wrapper.text()).toContain('Milk')
    })

    it('shows edit and delete buttons', () => {
        const wrapper = mountItem()
        expect(wrapper.find('.icon-edit').exists()).toBe(true)
        expect(wrapper.find('.icon-trash').exists()).toBe(true)
    })

    it('does not show an input in view mode', () => {
        const wrapper = mountItem()
        expect(wrapper.find('input').exists()).toBe(false)
    })
})

describe('entering edit mode', () => {
    it('shows input pre-filled with item text after clicking edit', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        const input = wrapper.find('input')
        expect(input.exists()).toBe(true)
        expect((input.element as HTMLInputElement).value).toBe('Milk')
    })

    it('hides the item text while editing', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        expect(wrapper.find('span.flex-1').exists()).toBe(false)
    })
})

describe('committing an edit', () => {
    it('calls router.put with updated value on Enter', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        const input = wrapper.find('input')
        await input.setValue('Oat Milk')
        await input.trigger('keyup.enter')
        expect(router.put).toHaveBeenCalledWith(
            '/shopping-list/1',
            { item: 'Oat Milk' },
            { preserveScroll: true },
        )
    })

    it('calls router.put on blur with a changed value', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        const input = wrapper.find('input')
        await input.setValue('Eggs')
        await input.trigger('blur')
        expect(router.put).toHaveBeenCalledWith(
            '/shopping-list/1',
            { item: 'Eggs' },
            { preserveScroll: true },
        )
    })

    it('trims whitespace before calling router.put', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        const input = wrapper.find('input')
        await input.setValue('  Butter  ')
        await input.trigger('keyup.enter')
        expect(router.put).toHaveBeenCalledWith(
            '/shopping-list/1',
            { item: 'Butter' },
            { preserveScroll: true },
        )
    })
})

describe('cancelling an edit', () => {
    it('does not call router.put on Escape', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        const input = wrapper.find('input')
        await input.setValue('Something else')
        await input.trigger('keyup.escape')
        expect(router.put).not.toHaveBeenCalled()
    })

    it('returns to view mode after Escape', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        await wrapper.find('input').trigger('keyup.escape')
        expect(wrapper.find('input').exists()).toBe(false)
        expect(wrapper.text()).toContain('Milk')
    })

    it('does not call router.put when value is unchanged', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        // value is already 'Milk' — no change
        await wrapper.find('input').trigger('keyup.enter')
        expect(router.put).not.toHaveBeenCalled()
    })

    it('does not call router.put when value is blank', async () => {
        const wrapper = mountItem()
        await wrapper.find('button:first-of-type').trigger('click')
        await wrapper.find('input').setValue('   ')
        await wrapper.find('input').trigger('keyup.enter')
        expect(router.put).not.toHaveBeenCalled()
    })
})

describe('deleting an item', () => {
    it('calls router.delete with the correct id', async () => {
        const wrapper = mountItem()
        const buttons = wrapper.findAll('button')
        await buttons[1].trigger('click')
        expect(router.delete).toHaveBeenCalledWith(
            '/shopping-list/1',
            { preserveScroll: true },
        )
    })
})
