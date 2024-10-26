<script setup>
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';

const lists = ref([]);
const newListName = ref('');
const newItemNames = ref({});

onMounted(() => {
  loadAllLists();
  console.log(lists);
  console.log(newItemNames);
  console.log(newListName);
});

// Lists
function loadAllLists() {
  axios.get('lists')
    .then(response => {
      lists.value = response.data;
      lists.value.forEach(list => {
        newItemNames.value[list.id] = '';
        getItemsByList(list.id);
      });
    })
    .catch(error => {
      console.error(error);
    });
}

function loadList(listId) {
  axios.get(`lists/${listId}`)
    .then(response => {
      const list = response.data;
      lists.value.push(list);
      getItemsByList(listId);
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
    .then(response => {
      const newListId = response.data.id;
      loadList(newListId);
      newListName.value = '';
    })
    .catch(error => {
      console.error(error);
    });
}

function updateListName(list) {
  const newName = list.newName.trim();
  if (newName === list.name) {
    alert('Name entered matches the existing name')

    return;
  }

  console.log(newName + '\n' + list.id + '\n' + list.name);

  axios.put(`lists/${list.id}`, { name: newName })
    .then(() => {
      list.name = newName;
    })
    .catch(error => {
      console.error(error);
    });
}

function deleteList(list) {
  if (confirm(`Are you sure you want to delete ${list.name}?`)) {
    axios.delete(`lists/${list.id}`)
      .then(() => {
        loadAllLists();
      })
      .catch(error => {
        console.error(error);
      });
  }
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
      getItemsByList(listId);
      newItemNames.value[listId] = ''; // Clear the input after submission
    })
    .catch(error => {
      console.error(error);
    });
}

function updateItemName(listId, item, newName) {
  item.editing = false;
  if (item.name === newName) {
    alert('Name entered matches the existing name')

    return
  }

  axios.put(`items/${item.id}`, { name: newName })
    .then(() => {
      getItemsByList(listId);
    })
    .catch(error => {
      console.error(error);
    });
}

function crossOutItem(item) {
  // Use same update method in ItemController?
}

function deleteItem(itemId) {
  axios.delete(`items/${itemId}`)
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
      <form @submit.prevent="createList">
        <button type="submit" class="text-gray-800 dark:text-gray-200">
          Create List
        </button>
        <br/>
        <input type="text" v-model="newListName" placeholder="Enter new list name" class="text-gray-800 dark:text-gray-200" />
      </form>
      <br/>
      <br/>
      <h1 class="text-gray-800 dark:text-gray-200">Lists:</h1> 
      <ul>
        <li v-for="list in lists" :key="list.id">
          <br/>
          <button class="delete-button" @click="deleteList(list)">X</button>
          <span :class="{ 'crossed-out': list.crossedOut }" class="text-gray-800 dark:text-gray-200">
            {{ list.name }}
          </span>
          <form @submit.prevent="updateListName(list)">
            <input type="text" v-model="list.newName" placeholder="Enter updated list name">
            <button type="submit" class="delete-button">Update list name</button>
          </form>
          <form @submit.prevent="createItem(list.id)">
            <input type="text" v-model="newItemNames[list.id]" placeholder="Enter new item name" class="text-gray-800 dark:text-gray-200" />
            <button type="submit" class="text-gray-800 dark:text-gray-200 delete-button" >
              Add Item
            </button>
          </form>
          <ul>
            <li v-for="item in list.items" :key="item.id">
              <template v-if="item.editing">
                <form @submit.prevent="updateItemName(list.id, item, item.newName)">
                  <input type="text" v-model="item.newName">
                  <button type="submit" class="delete-button">✔</button>
                </form>
              </template>
              <template v-else>
                <button class="delete-button" @click="deleteItem(item.id)">
                  X
                </button>
                <span :class="{ 'crossed-out': item.crossedOut }" class="text-gray-800 dark:text-gray-200">
                  {{ item.name }}
                </span>
                <button class="delete-button" @click="item.editing = true">
                  Edit
                </button>
              </template>
            </li>
          </ul>
        </li>
      </ul>
      <br/>
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
