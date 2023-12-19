<template>
    <ul class="list-group">
        <li
            v-for="task in tasks"
            :key="task.id"
            class="list-group-item d-flex justify-content-between align-items-center"
        >
            <div>
                <input
                    class="form-check-input me-1"
                    type="checkbox"
                    value=""
                    :checked="task.status"
                    :id="'check-' + task.id"
                    @change="$emit('toggleState', task.id)"
                >
                <label
                    :class="['form-check-label', task.status ? 'text-decoration-line-through' : '']"
                    :for="'check-' + task.id"
                >
                    {{ task.title }}
                </label>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary rounded-pill">
                    {{ task.date }}
                </span>
                <div class="br-hover d-flex align-items-center">
                    <svg
                        @click="edit(task.id)"
                        data-bs-toggle="modal"
                        data-bs-target="#taskModal"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        data-slot="icon"
                        class="w-6 h-6 ml-4"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>

                    <svg
                        @click="del(task.id)"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        data-slot="icon"
                        class="w-6 h-6"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </li>
    </ul>

    <!-- Modal -->
    <div
        class="modal fade"
        id="taskModal"
        tabindex="-1"
        aria-labelledby="taskModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="taskModalLabel">Изменить задачу</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div
                    v-if="editedTask"
                    class="modal-body"
                >
                    <input
                        v-model="editedTask.title"
                        type="text"
                        class="form-control"
                        placeholder="Задача"
                    >
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Отмена
                    </button>
                    <button
                        @click="updateTask"
                        type="button"
                        class="btn btn-primary"
                    >
                        Обновить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue"
import { Modal } from 'bootstrap'


defineEmits(['toggleState'])

const props = defineProps({
    tasks: {
        type: Array,
        required: true
    }
})

const del = (taskId) => {
    const taskIndex = props.tasks.findIndex(task => task.id === taskId)
    if (taskIndex === -1) return
    props.tasks.splice(taskIndex, 1)
    deleteTask(taskId)
}

const deleteTask = (taskId) => {
    axios.delete(`/task/${taskId}`)
}

const editedTask = ref(null)
const edit = (taskId) => {
    editedTask.value = props.tasks.find(task => task.id === taskId)
}

let taskModal = null
onMounted(() => {
   const taskModalEl = document.getElementById('taskModal')
    if (!taskModalEl) return
    taskModal = new Modal(taskModalEl)
    taskModalEl.addEventListener('hide.bs.modal', event => {
        editedTask.value = null
    })
})

const updateTask = () => {
    console.log(editedTask.value)
    if (!editedTask.value) return
    axios.put(`/task/${editedTask.value.id}`, editedTask.value)
        .then(res => {
            taskModal.hide()
        })
}
</script>


<style scoped>
.text-decoration-line-through {
    color: #999;
}
.br-hover svg:hover {
    color: #dc3545;
    cursor: pointer;
}
</style>
