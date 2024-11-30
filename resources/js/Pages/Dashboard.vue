<script setup>
import axios from 'axios';
import Item from '@/Components/Item.vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, onMounted, ref } from 'vue';

const lists = ref([]);
const newListName = ref('');

onMounted(() => {
  loadAllLists();
});

// Items
function createItem(listId) {
  const newItemName = lists.value.find(list => list.id === listId).newItemName.trim();

  if (newItemName === '') {
    alert('Please enter a list item name');
    return;
  } else if (lists.value.find(list => list.id === listId && list.items.find(item => item.name === newItemName))) {
    alert('Item name already used in this list');
    return;
  }

  axios.post(`lists/${listId}`, { name: newItemName })
    .then(() => {
      getItemsByList(listId);
      // Clear the input after submission
      lists.value.find(list => list.id === listId).newItemName = '';
    })
    .catch(error => {
      console.error(error);
    });
}

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

// Lists
function loadAllLists() {
  axios.get('lists')
    .then(response => {
      lists.value = response.data.map(list => ({
        id: list.id,
        name: list.name,
        crossed_out: list.crossed_out,
        items: [],
        newItemName: ''
      }));
      lists.value.forEach(list => {
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
      lists.value.push({
        id: list.id,
        name: list.name,
        crossed_out: list.crossed_out,
        items: [],
        newItemName: ''
      });
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
      newListName.value = ''; // Clear the input after submission
    })
    .catch(error => {
      console.error(error);
    });
}

function updateList(list, newName = null, crossedOut = null) {
  const updates = {};
  if (newName) {
    updates.name = newName;
  }
  if (crossedOut !== null) {
    updates.crossed_out = list.crossed_out = !crossedOut;
  }

  axios.put(`lists/${list.id}`, updates)
    .then(() => {
      if (newName) {
        list.name = newName;
        list.newName = ''; // Clear the input after submission
      }
    })
    .catch(error => {
      console.error(error);
    });
}

function deleteList(list) {
  if (confirm(`Are you sure you want to delete the "${list.name}" list?`)) {
    axios.delete(`lists/${list.id}`)
      .then(() => {
        // Locally remove the deleted list
        lists.value = lists.value.filter(l => l.id !== list.id);
      })
      .catch(error => {
        console.error(error);
      });
  }
}

// Custom CSS classes
const customButton = computed(() => {
  return 'text-gray-800 py-1 rounded-full mb-1 mt-1 mr-1';
});
const addButton = computed(() => {
  return `${customButton.value} bg-green-500 hover:bg-green-600 text-md px-1`;
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
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800
        dark:text-gray-200"
      >
        Dashboard
      </h2>
    </template>
    <div class="ml-3">
      <form @submit.prevent="createList">
        <button type="submit" :class="addButton" class="mt-5">
          ➕
        </button>
        <input type="text"
          v-model="newListName"
          placeholder="Enter new list name"
          :class="[standardText, inputFieldStyling]"
        />
      </form>
      <br/>

      <!-- Lists -->
      <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4
        gap-4"
      >
        <li v-for="list in lists" :key="list.id">
          <template v-if="list.editMode">
            <div class="flex items-center space-x-1">
              <form @submit.prevent="list.editMode = false">
                <button type="submit" :class="editButton">
                  Close edit mode
                </button>
              </form>
              <button :class="deleteButton" @click="deleteList(list)">
                Delete list
              </button>
            </div>
            <form @submit.prevent="updateList(list, list.newName, null)">
              <button type="submit" :class="editButton">✔</button>
              <input type="text" v-model="list.newName" :placeholder="list.name"
                autofocus :class="[standardText, inputFieldStyling]"
              >
            </form>
          </template>
          <template v-else>
            <button :class="editButton" @click="list.editMode = true">
              ✎
            </button>
            <span @click="updateList(list, null, list.crossed_out)"
              :class="[{ 'line-through': list.crossed_out }, standardText]"
              class="text-wrap text-xl break-words mr-2"
            >
              {{ list.name }}
            </span>
          </template>
          <form @submit.prevent="createItem(list.id)">
            <button type="submit" :class="addButton">➕</button>
            <input type="text"
              v-model="list.newItemName"
              placeholder="Item name"
              :class="[standardText, inputFieldStyling]"
            />
          </form>

          <!-- Items -->
          <ul>
            <li v-for="item in list.items" :key="item.id">
              <Item :list="list" :item="item" :lists="lists" />
            </li>
          </ul>
          <br/>
        </li>
      </ul>
      <br/>
    </div>
  </AuthenticatedLayout>
</template>
