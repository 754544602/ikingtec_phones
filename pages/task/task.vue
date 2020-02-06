<template>
	<view class="content">
		<span class="ikingtec_h1">任务</span>
		<view class="title_content">
			<span class="title">当前任务</span>
			<navigator url="/pages/task/createTask">
				<button class="primary">发起任务</button>
			</navigator>
		</view>
		<view>
			<navigator url="/pages/task/taskDetail">
			<view class="now_task_content" :style="{backgroundImage:`url(${taskBackgroundImage})`}">
				<span>任务1</span>
				<span>10:20</span>
				<h3>生态城</h3>
			</view>
			</navigator>
		</view>
		<view class="title_content">
			<span class="title blue">历史任务</span>
		</view>
		<scroll-view class="history_task_content" ref="history_task_content" scroll-y="true" >
			<view v-for="item in historyTaskList" :style="{backgroundImage:`url(${taskHistoryBackgroundImage})`}">
				<span>
					任务
				</span>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import taskBackgroundImage from "@/static/img/task_bg.png"
	import taskHistoryBackgroundImage from "@/static/img/history_task_bg.png"
	export default {
	    data() {
	        return {
	            email: '',
				taskBackgroundImage:taskBackgroundImage,
				taskHistoryBackgroundImage:taskHistoryBackgroundImage,
				historyTaskList:[{
					a:1
				},{
					a:1
				},{
					a:1
				},{
					a:1
				},{
					a:1
				},{
					a:1
				}]
	        }
	    },
	    methods: {
	    },
		onLoad() {
			console.log(this.$refs)
		}
	}
</script>

<style lang="scss">
	.title_content{
		margin-top: 50upx;
		margin-bottom: 40upx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.now_task_content{
		display: flex;
		justify-content: space-between;
		position: relative;
		height: 258upx;
		background-image: url("~@/static/img/task_bg.png");
		background-size: 100% 100%;
		border-radius: 20upx;
		box-shadow: 4upx 6px 10upx 0.5upx rgba(0, 0, 0, 0.43);
		padding: 24upx 23upx;
		font-size: 32upx;
		h3{
			position: absolute;
			right: 23upx;
			bottom: 62upx;
			font-size: 55upx;
		}
	}
	.title.blue{
		margin-top: 60upx;
	}
	.history_task_content{
		width: 100%;
		view{
			position: relative;
			float: left;
			background:url(../../static/img/history_task_bg.png) no-repeat ;
			background-size: 100% 100%;
			width: 296upx;
			height: 280upx;
			margin-bottom: 46upx;
		}
		view:nth-child(odd){
			margin-right: 65upx;
		}
		span{
			position: absolute;
			bottom: 20upx;
			right: 24upx;
			font-size: 24upx;
		};
	}
</style>
