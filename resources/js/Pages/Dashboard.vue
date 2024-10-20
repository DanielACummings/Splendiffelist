<script setup>
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';

const lists = ref([]);
const newListName = ref('');
const newItemNames = ref({}); // Object to hold new item names for each list

onMounted(() => {
  loadLists();
});

// Lists
function loadLists() {
  axios.get('lists')
    .then(response => {
      lists.value = response.data;
      lists.value.forEach(list => {
        newItemNames.value[list.id] = ''; // Initialize the new item name for each list
        getItemsByList(list.id);
      });
    })
    .catch(error => {
      console.error(error);
    });
}

function createList() {
  if (newListName.value.trim() === '') {
    alert('Please enter a list name');
    return;
  }
  if (lists.value.find(list => list.name === newListName.value)) {
    alert('List name already in use');
    return;
  }

  axios.post('/lists', { name: newListName.value })
    .then(() => {
      loadLists();
      newListName.value = '';
    })
    .catch(error => {
      console.error(error);
    });
}

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

function createItem(listId) {
  const newItemName = newItemNames.value[listId].trim();
  if (newItemName === '') {
    alert('Please enter a list item name');
    return;
  }

  axios.post(`lists/${listId}`, { name: newItemName })
    .then(() => {
      // Reload items for the list
      getItemsByList(listId);
      newItemNames.value[listId] = ''; // Clear the input after submission
    })
    .catch(error => {
      console.error(error);
    });
}

function deleteItem(listId, itemId) {
  axios.delete(`items/${itemId}`)
    .then(() => {
      this.getItemsByList(listId);
    })
    .catch(error => {
      console.error(error);
    });
}
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
      <template #header>
          <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
              Dashboard
          </h2>
      </template>

      <div class="py-12">
          <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
              <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                  <div class="p-6 text-gray-900 dark:text-gray-100">
                      You're logged in!
                  </div>
              </div>
          </div>
      </div>
      <div>
          <h1 class="text-gray-800 dark:text-gray-200">Lists:</h1> 
          <ul>
            <li v-for="list in lists" :key="list.id">
              <br/>
              <span :class="{ 'crossed-out': list.crossedOut }" class="text-gray-800 dark:text-gray-200">{{ list.name }}</span>
              <form @submit.prevent="createItem(list.id)">
                <input type="text" v-model="newItemNames[list.id]" placeholder="Enter new item name" class="text-gray-800 dark:text-gray-200" />
                <button type="submit" class="text-gray-800 dark:text-gray-200">Add Item</button>
              </form>
              <ul>
                <li v-for="item in list.items" :key="item.id">
                  <button class="delete-button" @click="deleteItem(list.id, item.id)">X</button>
                  <span :class="{ 'crossed-out': item.crossedOut }" class="text-gray-800 dark:text-gray-200">• {{ item.name }}</span>
                </li>
              </ul>
            </li>
          </ul>
          <br/>
          <form @submit.prevent="createList">
            <input type="text" v-model="newListName" placeholder="Enter new list name" class="text-gray-800 dark:text-gray-200" />
            <button type="submit" class="text-gray-800 dark:text-gray-200">Create List</button>
          </form>
        </div>
  </AuthenticatedLayout>
</template>

<style>
.delete-button {
  font-size: 12px;
  padding: 2px 4px;
  background-color: red;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  margin-right: 5px;
}
</style>
