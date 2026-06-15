import { reactive, computed } from 'vue'

const STORAGE_KEY = 'ittrack_cart'

function loadCart() {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
    } catch {
        return []
    }
}

function saveCart(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items))
}

const state = reactive({ items: loadCart() })

export function useCart() {
    const cartCount = computed(() => state.items.length)
    const inCart    = (id) => state.items.includes(id)

    function addToCart(id) {
        if (!state.items.includes(id)) {
            state.items.push(id)
            saveCart(state.items)
        }
    }

    function removeFromCart(id) {
        const idx = state.items.indexOf(id)
        if (idx !== -1) state.items.splice(idx, 1)
        saveCart(state.items)
    }

    function clearCart() {
        state.items.splice(0, state.items.length)
        saveCart(state.items)
    }

    return { cart: state.items, cartCount, inCart, addToCart, removeFromCart, clearCart }
}
