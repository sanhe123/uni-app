<template>
	<view>
		write
	</view>
</template>

<script>
	var loginRes;
	export default {
		data() {
			return {
				
			}
		},
		onLoad() {
			loginRes = this.checkLogin('../write/write',2);
			if(!loginRes){return ;}
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
