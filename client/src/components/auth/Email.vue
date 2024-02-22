<template>
    <div
        class="h-full flex justify-center items-center overflow-hidden before:absolute before:w-[60rem] before:h-[60rem] before:bg-purple before:rounded-full before:top-[-50%] before:left-[-15%] before:opacity-40 before:blur-lg after:w-[60rem] after:h-[60rem] after:absolute after:bg-blue after:rounded-full after:bottom-[-40%] after:right-[-20%] after:opacity-40 after:blur-lg">
        <div class="w-[40rem] h-[20rem] bg-purple bg-opacity-30 rounded-xl shadow-xl flex justify-center p-6">
            <div
                class="flex justify-center flex-col items-center drop-shadow-md text-center tracking-wider space-y-[2rem]">
                <span class="material-symbols-outlined text-6xl text-blue">
                    mark_email_read
                </span>
                <slot name="message" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>