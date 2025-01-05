<script setup>
import { useComputedStyles } from '../Composables/ComputedStyles.js';

const { deleteButton, editButton, inputFieldStyling, standardText }
  = useComputedStyles();

const props = defineProps({
  item: Object,
  list: Object,
  updateItem: Function,
  deleteItem: Function,
});
</script>

<template>
  <template v-if="list.editMode">
    <div class="flex items-center space-x-2">
      <button :class="deleteButton" @click="deleteItem(item.id, list.id)">
        x
      </button>
      <form @submit.prevent="updateItem(list.id, item,
        { newName: item.newName })"
      >
        <button type="submit" :class="editButton">✔</button>
        <input
          :class="[standardText, inputFieldStyling]"
          type="text"
          v-model="item.newName"
          :placeholder="item.name"
          autofocus
          class="w-[200px]"
        />
      </form>
    </div>
  </template>
  <template v-else>
    <span class="text-wrap break-words"
      @click="updateItem(list.id, item, { crossedOut: item.crossed_out })"
      :class="[{ 'line-through': item.crossed_out }, standardText]"
    >
      • {{ item.name }}
    </span>
  </template>
</template>
