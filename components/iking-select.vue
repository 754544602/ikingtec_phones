<template>
		<button type="primary" class="mini-btn" size="mini">
			<slot></slot>
		<uni-icon type="arrowdown" size="20" color="#ffffff"></uni-icon>
		</button>
</template>

<script>
	import uniIcon from './uni-icons/uni-icons.vue'
	export default {
		components:{
			uniIcon
		},
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss">
	.mini-btn{
		line-height: 56upx;
		border-radius: 10upx;
		background-color: #2ba3ef;
		padding:0 10upx 0 27upx;
		display:flex;
		justify-content:space-between;
		font-size:32upx;
		uni-text{
			font-size: 16upx;
		}
	}
</style>
