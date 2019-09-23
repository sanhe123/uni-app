<template>
	<view>
		my
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		onLoad() {
			loginRes = this.checkLogin('../my/my',2);
			if(!loginRes){return ;}
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
