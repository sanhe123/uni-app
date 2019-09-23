<template>
	<view>
		my
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
			loginRes = this.checkLogin('../my/my',2);
			if(!loginRes){return ;}
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
