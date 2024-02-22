<template>
    <div class="w-full h-full flex justify-center items-center overflow-hidden before:absolute before:w-[60rem] before:h-[60rem] before:bg-purple before:rounded-full before:top-[-50%] before:left-[-15%] before:opacity-40 before:blur-lg after:w-[60rem] after:h-[60rem] after:absolute after:bg-blue after:rounded-full after:bottom-[-40%] after:right-[-20%] after:opacity-40 after:blur-lg">
        <div class="w-[40rem] h-[20rem] bg-purple bg-opacity-30 rounded-xl shadow-xl flex justify-center p-6">
            <div class="flex flex-col items-center drop-shadow-md text-center tracking-wider">
                <span class="material-symbols-outlined text-6xl text-blue">
                    forward_to_inbox
                </span>
                <p class="">
                    Chúng tôi đã gửi một email xác nhận đến địa chỉ email của bạn,
                    vui lòng xác nhận để hoàn thành đăng ký.
                </p>
                <p class="mt-[2rem] text-2xl">Chưa nhận được email? <span class="text-blue underline cursor-pointer opacity-80 hover:opacity-100">Gửi lại</span></p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>

<style lang="scss" scoped>

</style>