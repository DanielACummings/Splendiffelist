import { computed } from 'vue';

const computedStyles = () => {
    // Buttons
    const customButton = computed(() =>
        'text-gray-800 py-1 rounded-full mb-1 mt-1 mr-1');
    const addButton = computed(() =>
        `${customButton.value} bg-green-500 hover:bg-green-600 text-md px-1`);
    const editButton = computed(() =>
        `${customButton.value} bg-yellow-500 hover:bg-yellow-600 px-2`);
    const deleteButton = computed(() =>
        `${customButton.value} bg-red-500 hover:bg-red-600 px-3`);

    // Other
    const standardText = computed(() => 'text-gray-800 dark:text-gray-200');
    const inputFieldStyling = computed(() =>
        'bg-gray-800 dark:bg-gray-800 mb-1');

    return {
        customButton,
        editButton,
        deleteButton,
        addButton,
        standardText,
        inputFieldStyling,
    };
};

export default computedStyles;
