<script setup>
import axios from 'axios';
import { computed } from 'vue';

// Items
function getItemsByList(listId) {
  axios.get(`lists/${listId}/items`)
    .then(response => {
      const list = lists.value.find(list => list.id === listId);
      list.items = response.data;
    })
    .catch(error => {
      console.error(error);
    });
}

function updateItem(listId, item, optionalParams = {}) {
  const { newName, crossedOut } = optionalParams;
  const updates = {};
  if (newName) {
    updates.name = newName;
  }
  if (crossedOut !== null) {
    updates.crossed_out = item.crossed_out = !crossedOut;
  }

  axios.put(`items/${item.id}`, updates)
    .then(() => {
      getItemsByList(listId);
    })
    .catch(error => {
      console.error(error);
    });
}

function deleteItem(itemId, listId) {
  axios.delete(`items/${itemId}`)
    .then(() => {
      getItemsByList(listId);
    })
    .catch(error => {
      console.error(error);
    });
}

// Custom CSS classes
const customButton = computed(() => {
  return 'text-gray-800 py-1 rounded-full mb-1 mt-1 mr-1';
});
const editButton = computed(() => {
  return `${customButton.value} bg-yellow-500 hover:bg-yellow-600 px-2`;
});
const deleteButton = computed(() => {
  return `${customButton.value} bg-red-500 hover:bg-red-600 px-3`;
});
const standardText = computed(() => {
  return 'text-gray-800 dark:text-gray-200';
});
const inputFieldStyling = computed(() => {
  return 'bg-gray-800 dark:bg-gray-800 mb-1';
});
</script>

<template>
  <template v-if="list.editMode">
    <button :class="deleteButton" @click="deleteItem(item.id, list.id)">
      x
    </button>
    <form @submit.prevent="updateItem(list.id, item, { newName: item.newName })">
      <button type="submit" :class="editButton">✔</button>
      <input :class="[standardText, inputFieldStyling]"
        type="text"
        v-model="item.newName"
        style="width: 204px;"
        :placeholder="item.name"
        autofocus
      />
    </form>
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
