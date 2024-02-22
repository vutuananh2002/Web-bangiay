import { defineStore } from "pinia";
import { ref, reactive } from "vue";

export const useGlobalStore = defineStore('global', () => {
    // Selected filter
    const selected = reactive({
        categories: [],
        colors: [],
        manufactures: [],
        types: [],
        sizes: [],
        prices: [],
        rating: '',
        order: '',
    });
    const search = ref<string>('');
    
    const resetSelected = () => {
        selected.categories = [];
        selected.colors = [];
        selected.manufactures = [];
        selected.types = [];
        selected.sizes = [];
        selected.prices = [];
        selected.rating = '';
        selected.order = '';
    }

    return {
        selected,
        search,
        resetSelected,
    }
});