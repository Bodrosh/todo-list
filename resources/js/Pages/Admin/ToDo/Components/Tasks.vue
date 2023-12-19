<template>
    <div class="input-group mb-3">
        <input
            v-model="newTaskText"
            type="text"
            class="form-control"
            placeholder="Новая задача"
        >
        <button
            @click="addTask"
            class="btn btn-outline-secondary"
            type="button"
        >Добавить</button>
    </div>
    <TaskList
        :tasks="tasks"
        @toggleState="toggleState"
    />
</template>

<script setup>
import TaskList from "@/Pages/Admin/ToDo/Components/TaskList.vue";
import {ref} from "vue";

const props = defineProps({
    tasks: Array
})

const newTaskText = ref('')
const addTask = () => {
    if (!newTaskText.value) return

    axios.post('/task', {
        title: newTaskText.value
    }).then(res => {
        newTaskText.value = ''
        props.tasks.unshift(res.data)
    })
}

const toggleState = (taskId) => {
    const task = props.tasks.find(task => task.id === taskId)
    if (!task) return
    task.status = !task.status
    toggleTask(taskId)
}

const toggleTask = (taskId) => {
    axios.post(`/task/${taskId}/toggle`)
        .then(res => {
            console.log(res)
        })
}
</script>

<style scoped>

</style>
