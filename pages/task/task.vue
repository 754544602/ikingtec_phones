<template>
	<view class="content">
		<span class="ikingtec_h1">任务</span>
		<view class="title_content" v-if="userType=='demand'">
			<span class="title">当前任务<span v-if="beingTaskList.length==0">(暂无任务)</span></span>
			<navigator url="/pages/task/createTask">
				<button class="primary">发起任务</button>
			</navigator>
		</view>
		<view class="title_content" v-if="userType=='dronePilot'">
			<span class="title">任务计划<span v-if="beingTaskList.length==0">(暂无任务)</span></span>
			<navigator url="/pages/task/createTask">
				<button class="primary red action">紧急任务</button>
			</navigator>
		</view>
		<button class="primary blue date" @click="open" v-if="userType=='dronePilot'">{{date}}</button>
		<view class="beingTask">
			<navigator :url="'/pages/task/taskDetail?taskId='+item.id" v-for="item,index in beingTaskList" :key="index">
			<view class="now_task_content" :style="{backgroundImage:`url(${taskBackgroundImage})`}">
				<span style="padding-right: 20upx;">{{item.name}}</span>
				<span>{{item.startTime}}</span>
				<h3>{{item.statusName}}</h3>
			</view>
			</navigator>
		</view>
		<view class="title_content">
			<span class="title blue">历史任务<span v-if="historyTaskList.length==0">(暂无任务)</span></span>
		</view>
		<scroll-view class="history_task_content" ref="history_task_content" scroll-y="true" >
			<view class="history_task" :style="{backgroundImage:`url(${taskHistoryBackgroundImage})`}" @click="toHistoryDetail(item.id)" v-for="item,index in historyTaskList" :key="index">
				<label>已结束</label>
				<span>
					{{item.name}}
				</span>
			</view>
		</scroll-view>
		<uni-calendar class="calendar" ref="calendar" :insert="false" :date="date" :startDate="date" @confirm="dateConfirm" @close="dateClose"/>
	</view>
</template>

<script>
	import uniCalendar from '@/components/uni-calendar/uni-calendar.vue';
	import taskBackgroundImage from "@/static/img/task_bg.png"
	import taskHistoryBackgroundImage from "@/static/img/history_task_bg.png"
	function nowDate(){
		let nowDate = new Date();
		return nowDate.getFullYear() + '-' + ('0'+(nowDate.getMonth() + 1)).slice(-2) + '-' + ('0'+nowDate.getDate()).slice(-2);
	}
	export default {
		components:{
			uniCalendar
		},
	    data() {
	        return {
	            email: '',
				taskBackgroundImage:taskBackgroundImage,
				taskHistoryBackgroundImage:taskHistoryBackgroundImage,
				historyTaskList:[],
				beingTaskList:[],
				date:nowDate(),
				userType:this.$userInfo().userType
	        }
	    },
	    methods: {
			open() {
				this.$refs.calendar.open();
			},
			dateClose(){
				
			},
			dateConfirm(e){
				this.date = e.fulldate;
				this.getBeingList()
			},
			toHistoryDetail(id){
				uni.navigateTo({
					url:'/pages/task/taskDetail?taskId='+id
				})
			},
			getHistoryList(){
				this.$request.post("/task/taskList",{
					taskHistory:'1'
				}).then(res=>{
					console.log(res);
					if(res.flag==0){
						this.historyTaskList= res.data.list.map(e=>{
							e.statusName = '已结束'
							return e
						})
					}
				})
			},
			getBeingList(){
				let postData = {}
				if(this.userType=="dronePilot"){
					postData.time = this.date
				}
				this.$request.post("/task/taskList",postData).then(res=>{
					if(res.flag==0){
						this.beingTaskList = res.data.list.map(function(e){
							switch (e.status){
								case 'waitApprove':
									e.statusName = '待审批'
								break;
								case 'waitStart':
									e.statusName = '审批通过'
								break;
								case 'noApprove':
									e.statusName = '拒绝审批'
								break;
								case 'being':
									e.statusName = '执行中'
								break;
								case 'waitEval':
									e.statusName = '待评价'
								break;
								case 'finish':
									e.statusName = '已结束'
								break;
							}
							return e
						})
						this.beingTaskList = this.beingTaskList.filter(function(e){
							return e.status != 'finish';
						})
					}
				})
			}
	    },
		onShow(){
			this.getBeingList()
			this.getHistoryList()
		},
		onLoad() {
			uni.loadFontFace({
			    family: 'ikingFont',
			    // 本地字体路径需转换为平台绝对路径
				// #ifdef APP-PLUS
			    source: `url(${plus.io.convertLocalFileSystemURL('/static/font/SourceHanSansCN-Bold.otf')})`,
			    // #endif
				// #ifdef H5
				source: `url(${'/static/font/SourceHanSansCN-Bold.otf'})`,
				// #endif
				success() {
			        console.log('success')
			    },
			    fail(e) {
			        console.log('fail',JSON.stringify(e))
			    }
			})
		}
	}
	
</script>

<style lang="scss">
	.title_content{
		margin-top: 50upx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.beingTask{
		margin-top: 40upx;
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
		margin-top: 20upx;
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
		.history_task{
			position: relative;
			float: left;
			background:url(../../static/img/history_task_bg.png) no-repeat ;
			background-size: 100% 100%;
			width: 296upx;
			height: 280upx;
			margin-bottom: 46upx;
		}
		.history_task:nth-child(odd){
			margin-right: 65upx;
		}
		span{
			position: absolute;
			bottom: 20upx;
			right: 24upx;
			font-size: 24upx;
			display: block;
			width: 250upx;
			text-align: right;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		label{
			position:absolute;
			bottom: 50upx;
			right: 24upx;
			font-size: 24upx;
		}
	}
	.primary.blue.date{
		background: #fff;
		color: #1d94df;
	}
</style>
