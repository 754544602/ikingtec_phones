<template>
		<button class="mini-btn" :class="name">
			<slot></slot>
		<uni-icons type="arrowdown" size="20" color="#ffffff"></uni-icons>
		</button>
</template>

<script>
	import uniIcons from '@/components/uni-icons/uni-icons.vue'
	export default {
		props:{
			name:{
				type:String
			}
		},
		components:{
			uniIcons
		},
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss">
	.mini-btn{
		font-family: ikingFont !important;
		line-height: 56upx !important;
		border-radius: 10upx;
		background-color: #2ba3ef;
		padding:0 8upx 0 20upx !important;
		display:inline-flex;
		justify-content:space-between !important;
		font-size:32upx !important;
		color: #fff;
		box-sizing: border-box;
		&:after {
			border: none ;
		}
		uni-text{
			font-size: 16upx !important;
		}
	}
</style>
