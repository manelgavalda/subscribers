<template>
	<div>
		<p v-for="error in errors">
			<ul>
				<li v-text="error" class="red-text"></li>
			</ul>
		</p>
	</div>
</template>
<script>
	export default {
		props: ['errors']
	}
</script>
<style>
    .red-text {
        color: red;
    }
</style>
