// Composables/useSortable.js
import { onMounted, onBeforeUnmount } from 'vue';
import Sortable from 'sortablejs';

/**
 * Vue composable for Sortable.js integration
 *
 * @param {Ref<HTMLElement>} containerRef - Vue ref to the container element
 * @param {Object} options - Sortable.js options
 * @returns {Object} - Sortable instance
 */
export function useSortable(containerRef, options = {}) {
  let sortableInstance = null;

  onMounted(() => {
    // Make sure the container ref exists
    if (!containerRef.value) {
      console.warn('useSortable: containerRef is not attached to an element');
      return;
    }

    // Default options that work well with Vue
    const defaultOptions = {
      animation: 150,
      handle: '.handle',
      ghostClass: 'sortable-ghost',
      chosenClass: 'sortable-chosen',
      dragClass: 'sortable-drag',
    };

    // Create the Sortable instance
    sortableInstance = Sortable.create(
      containerRef.value,
      { ...defaultOptions, ...options }
    );
  });

  onBeforeUnmount(() => {
    // Clean up the Sortable instance
    if (sortableInstance) {
      sortableInstance.destroy();
      sortableInstance = null;
    }
  });

  return {
    getInstance: () => sortableInstance
  };
}
