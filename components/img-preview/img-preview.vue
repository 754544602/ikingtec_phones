<template>
	<view>
		<view class="mask"  @click="clickPic">
			<swiper @change="changeSwiper" class="my_swiper" :current="current" :circular="circular" :indicator-dots="indicatorDots" :autoplay="autoplay" :duration="duration">
				<swiper-item class="swiper-item" v-for="(x, y) in picList" :key="y">
					<!-- <view class="bg_img" :style="{ backgroundImage: 'url('+x.url+')'}"></view> -->
					<image :src="x.url" mode="widthFix" class="image"></image>
				</swiper-item>
			</swiper>
			<view class="page" v-if="picList.length>0">{{ current + 1 }} / {{ picList.length }}</view>
		</view>
	</view>
</template>

<script>
export default { 
	props: {
		/**
		* 自动获取焦点
		*/
		current: {
			type: [Number, String],
			default: 0
		},
		// 是否自动播放
		autoplay: {
			type: [Boolean],
			default: false
		},
		// 是否显示面板指示点
		indicatorDots: {
			type: [Boolean],
			default: false
		},
		// 图片列表
		picList: {
			type: [Array],
			default: []
		},
	},
	data() {
		return {
			duration: 500,
			circular: true,
			isShowSwiper: false
		};
	},
	methods: {
		clickPic(index) {
			// this.current = index;
			// this.isShowSwiper = true;
			this.$emit('clickPic')

		},
		changeSwiper(e) {
			this.current = e.target.current;
			this.$emit('changeSwiper', e)
		}
	}
};
</script>

<style lang="scss" scoped>
.page {
	position: fixed;
	z-index: 1001;
	color: #fff;
	bottom: 20rpx;
	text-align: center;
	width: 100%;
}
.mask {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	display: flex;
	justify-content: center;
	align-items: center;
	background-color: #000;
	z-index: 1000;
	> .my_swiper {
		width: 100%;
		// height: auto;
		.bg_img {
			background-size: 100% auto;
			background-repeat: no-repeat;
			background-position:center;
			width: 100%;
			height: 100%;
		}
		.image{
			width: 100%;
		}
		.swiper-item{
			display: flex;
			align-items: center;
		}
	}
}

.pic_list {
	display: flex;
	flex-flow: row wrap;
	> view {
		flex: 0 0 33.3vw;
		height: 33.3vw;
		padding: 1vw;
		> image {
			width: 100%;
			height: 100%;
		}
	}
}
</style>
