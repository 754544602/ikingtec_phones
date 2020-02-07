<template>
	<view>
		<view class="swiperPanel" @touchstart="startMove" @touchend="endMove">
			<view class="swiperItem" v-for="(item, index) in swiperList" :key="index" :style="{transform: itemStyle[index].transform, zIndex: itemStyle[index].zIndex, opacity: itemStyle[index].opacity}">
				<view class="children">
					<image class="pic" :src="item.url"></image>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			swiperList: {
				type: Array,
				default: []
			}
		},
		data() {
			return {
				slideNote: {
					x: 0,
					y: 0
				},
				screenWidth: 0,
				itemStyle: []
			};
		},
		created() {
			var macInfo = uni.getSystemInfoSync();
			this.screenWidth = macInfo.screenWidth;
			// 计算swiper样式
			this.swiperList.forEach((item, index) => {
				this.itemStyle.push(this.getStyle(index))
			})
		},
		methods: {
			getStyle(e) {
				if (e > this.swiperList.length / 2) {
					var right = this.swiperList.length - e
					return {
						transform: 'scale(' + (1 - right / 10) + ') translate(-' + (right * 9) + '%,0px)',
						zIndex: 9999 - right,
						opacity: 0.8 / right
					}
				} else {
					return {
						transform: 'scale(' + (1 - e / 10) + ') translate(' + (e * 9) + '%,0px)',
						zIndex: 9999 - e,
						opacity: 0.8 / e
					}
				}
			},
			startMove(e) {
				this.slideNote.x = e.changedTouches[0] ? e.changedTouches[0].pageX : 0;
				this.slideNote.y = e.changedTouches[0] ? e.changedTouches[0].pageY : 0;
			},
			endMove(e) {
				var newList = JSON.parse(JSON.stringify(this.itemStyle))
				if ((e.changedTouches[0].pageX - this.slideNote.x) < 0) {
					// 向左滑动
					var last = [newList.pop()]
					newList = last.concat(newList)
				} else {
					// 向右滑动
					newList.push(newList[0])
					newList.splice(0, 1)
				}
				this.itemStyle = newList
			}
		}
	}
</script>

<style lang="scss">
	.swiperPanel {
		margin: 20rpx 0;
		height: 344rpx;
		width: 100%;
		overflow: hidden;
		position: relative;

		.swiperItem {
			height: 100%;
			width: 100%;
			position: absolute;
			top: 0;
			left: 0;
			transition: all .5s;

			.children {
				height: 100%;
				width: 580rpx;
				margin: 2rpx auto;

				.pic {
					height: 100%;
					width: 100%;
					border-radius: 20px;
					// box-shadow: 0 0 10px #333;
				}
			}
		}
	}
</style>
