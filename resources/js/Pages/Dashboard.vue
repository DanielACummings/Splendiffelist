<script setup>
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, onMounted, ref } from 'vue';

const lists = ref([]);
const newListName = ref('');
const newItemNames = ref({});

onMounted(() => {
  loadAllLists();
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

function updateList(list, newName = null, crossedOut = null) {
  list.editing = false;
  if (list.name === newName) {
    alert('Name entered matches the existing name')

    return;
  }

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

function updateItem(listId, item, optionalParams = {}) {
  const { newName, crossedOut } = optionalParams;
  item.editing = false;
  if (item.name === newName) {
    alert('Name entered matches the existing name')

    return
  }

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
const addButton = computed(() => {
  return `${customButton.value} bg-green-500 hover:bg-green-600 text-sm px-1`;
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
      <h2 class="text-xl
        font-semibold
        leading-tight
        text-gray-800
        dark:text-gray-200"
      >
        Dashboard
      </h2>
    </template>
    <div>
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
      <ul>
        <li v-for="list in lists" :key="list.id">
          <template v-if="list.editing">
            <form @submit.prevent="updateList(list, list.newName, null)">
              <input type="text" v-model="list.newName" autofocus
                placeholder="List name"
                :class="[standardText, inputFieldStyling]"
              >
              <button type="submit" :class="editButton">
                ✔
              </button>
            </form>
          </template>
          <template v-else>
            <button :class="deleteButton" @click="deleteList(list)">
              x
            </button>
            <button :class="editButton" @click="list.editing = true">
              ✎
            </button>
            <span @click="updateList(list, null, list.crossed_out)"
              :class="[{ 'line-through': list.crossed_out }, standardText]"
              class="mr-2"
            >
              {{ list.name }}
            </span>
          </template>
          <form @submit.prevent="createItem(list.id)">
            <button type="submit" :class="addButton">
              ➕
            </button>
            <input type="text"
              v-model="newItemNames[list.id]"
              placeholder="Item name"
              :class="[standardText, inputFieldStyling]"
            />
          </form>
          <ul>
            <li v-for="item in list.items" :key="item.id">
              <template v-if="item.editing">
                <form @submit.prevent="updateItem(list.id, item, { newName: item.newName })">
                  <input type="text" v-model="item.newName" autofocus>
                  <button type="submit" class="editButton">
                    ✔
                  </button>
                </form>
              </template>
              <template v-else>
                <button :class="deleteButton"
                  @click="deleteItem(item.id, list.id)"
                >
                  x
                </button>
                <button :class="editButton" @click="item.editing = true">
                  ✎
                </button>
                <span @click="updateItem(list.id, item,
                  { crossedOut:item.crossed_out })"
                  :class="[{ 'line-through': item.crossed_out }, standardText]"
                >
                  {{ item.name }}
                </span>
              </template>
            </li>
          </ul>
          <br/>
        </li>
      </ul>
      <br/>
    </div>
  </AuthenticatedLayout>
</template>
