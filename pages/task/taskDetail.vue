<template>
	<view class="">
		<uni-nav-bar style="height:100upx" @clickLeft="back" left-icon="back" :title="title" backgroundColor="#1d94df"
		 :shadow="false" :border="false" fixed="true" color="#ffffff">
			<view slot="right">
				<span class="detail_status" :class="{'red':statusCode==6||statusCode==3,'yellow':statusCode==5,'blue':statusCode<3,'green':statusCode==4}">{{status}}</span>
			</view>
		</uni-nav-bar>
		<view class="content">
			<view class="detail_title_content">
				<view class="type_content">
					<button v-if="taskType==1" class="primary">时效性任务</button>
					<button v-if="taskType==2" class="primary">周期任务</button>
					<button v-if="taskType==3" class="primary" style="color: #e60012;">紧急任务</button>
				</view>
				<view class="detail_title_right">
					<span>2020-01-01 12:00</span>
				</view>
			</view>
			<view class="switch_content">
				<view>
					<button class="switch" :class="[action==1?'action':'']" @click="active(1)">详情</button>
				</view>
				<view v-if="taskType==1">
					<button class="switch" :class="[action==2?'action':'']" @click="active(2)">图片</button>
				</view>
				<view v-if="taskType==2&&statusCode>2&&userType!='dronePilot'">
					<button class="switch" :class="[action==5?'action':'']" @click="active(5)">违规判定</button>
				</view>
				<view v-if="taskType==1">
					<button class="switch" :class="[action==3?'action':'']" @click="active(3)">视频</button>
				</view>
				<view v-if="taskType==2&&statusCode>2">
					<button class="switch" :class="[action==6?'action':'']" @click="active(6)">标准</button>
				</view>
				<view v-if="taskType==1">
					<button class="switch" :class="[action==4?'action':'']" @click="active(4)">沟通记录</button>
				</view>
			</view>
			<view class="view_content" id="view" ref='view' v-if="action==1">
				<view class="span_content">
					<view class="span_label">
						<span>设备名称：</span>
						<span>无人机1</span>
					</view>
					<view class="span_label">
						<span>飞手名称：</span>
						<span>飞手1</span>
					</view>
					<view class="span_label">
						<span>飞手位置：</span>
						<span>生态城</span>
					</view>
					<view class="span_label" v-if="taskType==2">
						<span>巡检周期：</span>
						<span>每日 9：00，每日14：00</span>
					</view>
				</view>
				<span class="title blue">任务地点</span>
				<map class="map" id="map1" :latitude="latitude" :longitude="longitude">
				</map>
				<span class="title blue">任务描述</span>
				<scroll-view scroll-y class="desc_scroll_content">
					<p>
						中新天津生态城是中国、新加坡两国政府战略性合作项目。生态城市的建设显示了中新两国政府应对全球气候变化、加强环境保护、节约资源和能源的决心，为资源节约型、环境友好型社会的建设提供积极的探讨和典型示范。
						生态城坐落于天津滨海新区(距离天津市中心40公里处)。为满足中国城市化发展的需求，占地30平方公里的生态城以
					</p>
				</scroll-view>
				<view v-if="tatusCode<4">
					<span class="title blue">审批意见</span>
					<scroll-view scroll-y class="desc_scroll_content">
						<p>
							同意
						</p>
					</scroll-view>
				</view>
				<view v-if="statusCode>=5">
					<span class="title blue">满意度评价</span>
					<uni-rate class="rate" :value="rateValue" :disabled="isDetail" @change="rateChange" :color="rateColor"
					 :activeColor="rateActiveColor">
						<span class="rateSpan">{{rateSpan}}</span>
					</uni-rate>
					<textarea placeholder="请输入意见" class="textarea" auto-height v-if="statusCode==5" />
					<scroll-view scroll-y class="desc_scroll_content" v-if="statusCode==6">
					<p>
						中新天津生态城是中国、新加坡两国政府战略性合作项目。生态城市的建设显示了中新两国政府应对全球气候变化、加强环境保护、节约资源和能源的决心，为资源节约型、环境友好型社会的建设提供积极的探讨和典型示范。
						生态城坐落于天津滨海新区(距离天津市中心40公里处)。为满足中国城市化发展的需求，占地30平方公里的生态城以
					</p>
				</scroll-view>
			</view>
			<form v-if="userType=='dronePilot'">
				<view class="iking-flex-left">
					<span class="title blue">数据上传</span>
					<button type="primary" size="mini" class="mini-btn" style="background-color: #1d94df">提交</button>
				</view>
				<view class="uni-list list-pd">
					<view class="uni-list-cell cell-pd">
						<view class="uni-uploader">
							<view class="uni-uploader-head">
								<view class="uni-uploader-title">点击可预览选好的图片</view>
								<view class="uni-uploader-info">{{imageList.length}}/9</view>
							</view>
							<view class="uni-uploader-body">
								<view class="uni-uploader__files">
									<block v-for="(image,index) in imageList" :key="index">
										<view class="uni-uploader__file">
											<image class="uni-uploader__img" :src="image" :data-src="image" @tap="previewImage"></image>
										</view>
									</block>
									<view class="uni-uploader__input-box">
										<view class="uni-uploader__input" @tap="chooseImage"></view>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				<template v-if="!src">
					<view class="uni-hello-addfile" @tap="chooseVideo">+ 添加视频</view>
				</template>
				<template v-else>
					<video :src="src" class="video"></video>
				</template>
			</form>
		</view>
		<scroll-view class="view_content" v-show="action==2">
			<image src="../../static/DSC09188.JPG" class="image" lazy-load='true' mode="aspectFill"></image>
			<image src="../../static/DSC09189.JPG" class="image" lazy-load='true' mode="aspectFill"></image>
			<image src="../../static/DSC09190.JPG" class="image" lazy-load='true' mode="aspectFill"></image>
			<image src="../../static/DSC09191.JPG" class="image" lazy-load='true' mode="aspectFill"></image>
			<image src="../../static/DSC09192.JPG" class="image" lazy-load='true' mode="aspectFill"></image>
			<image src="../../static/DSC09193.JPG" class="image" lazy-load='true' mode="aspectFill"></image>
		</scroll-view>
		<view class="view_content" v-show="action==3">
			<video id="video1" style="width: 100%;" src="https://img.cdn.aliyun.dcloud.net.cn/guide/uniapp/%E7%AC%AC1%E8%AE%B2%EF%BC%88uni-app%E4%BA%A7%E5%93%81%E4%BB%8B%E7%BB%8D%EF%BC%89-%20DCloud%E5%AE%98%E6%96%B9%E8%A7%86%E9%A2%91%E6%95%99%E7%A8%8B@20181126-lite.m4v" controls></video>
		</view>
		<view class="bottom_content">
			<button class="primary blue" @click="submit">{{submitText}}</button>
		</view>
	</view>
	</view>
</template>

<script>
	import uniRate from '@/components/uni-rate/uni-rate.vue'
	import uniNavBar from "@/components/uni-nav-bar/uni-nav-bar.vue"
	var sourceType = [
		['camera'],
		['album'],
		['camera', 'album']
	]
	var sizeType = [
		['compressed'],
		['original'],
		['compressed', 'original']
	]
	export default {
		components: {
			uniRate,
			uniNavBar
		},
		data() {
			return {
				title:"任务1",
				status:"已结束",
				statusCode:4,
				submitText:"提交",
				taskType:1,
				action: 1,
				userType:'dronePilot',
				latitude: 39.119929,
				longitude: 117.752705,
				rateColor: "#b5b5b5",
				rateActiveColor: "#1d94df",
				rateValue: 5,
				isDetail:false,
				video:null,
				imageList: [],
				videoSrc:'',
				sourceTypeIndex: 1,
				sourceType: ['拍照', '相册', '拍照或相册'],
				sizeTypeIndex: 2,
				sizeType: ['压缩', '原图', '压缩或原图'],
				countIndex: 8,
				count: [1, 2, 3, 4, 5, 6, 7, 8, 9]
			};
		},
		methods: {
			active(action) {
				this.action = action;
				this.video.pause();
			},
			rateChange(value) {
				this.rateValue = value.value;
			},
			back(){
				uni.navigateBack();
			},
			submit(){
				
			},
			chooseImage: async function() {
					if (this.imageList.length === 9) {
						let isContinue = await this.isFullImg();
						console.log("是否继续?", isContinue);
						if (!isContinue) {
							return;
						}
					}
					uni.chooseImage({
						sourceType: sourceType[this.sourceTypeIndex],
						sizeType: sizeType[this.sizeTypeIndex],
						count: this.imageList.length + this.count[this.countIndex] > 9 ? 9 - this.imageList.length : this.count[this.countIndex],
						success: (res) => {
							this.imageList = this.imageList.concat(res.tempFilePaths);
						}
					})
				},
				isFullImg: function() {
					return new Promise((res) => {
						uni.showModal({
							content: "已经有9张图片了,是否清空现有图片？",
							success: (e) => {
								if (e.confirm) {
									this.imageList = [];
									res(true);
								} else {
									res(false)
								}
							},
							fail: () => {
								res(false)
							}
						})
					})
				},
				previewImage: function(e) {
					var current = e.target.dataset.src
					uni.previewImage({
						current: current,
						urls: this.imageList
					})
				},
				chooseVideo: function () {
					uni.chooseVideo({
						sourceType: sourceType[this.sourceTypeIndex],
						success: (res) => {
							this.src = res.tempFilePath
						}
					})
				},
			_ready(){
				this.userType = 'dronePilot'
				this.video=uni.createVideoContext('video1', this)
				uni.setNavigationBarTitle({
					title: "任务1"
				})
				switch (this.statusCode){
					case 1:
						this.status = "待审批"
						this.submitText='关闭'
						this.submit = function(){
							uni.navigateBack()
						}
						break;
					case 2:
						this.status = "通过"
						this.submitText='开始任务'
						this.submit = function(){
							this._ready()
						}
						break;
					case 3:
						this.status = "拒绝"
						this.submitText='修改'
						this.submit = function(){
							uni.redirectTo({
								url:"/pages/task/createTask"
							})
						}
						break;
					case 4:
						this.status = "执行中"
						if(this.userType == 'dronePilot'){
							this.submitText='取消任务'
							this.submit=function(){
								uni.showModal({
									title: '是否取消任务？',
									showCancel: true,
									cancelText: '取消',
									confirmText: '确认',
									success: res => {
										
									}
								});
							}
						}else{
							this.submitText='实时'
							this.submit=function(){
								uni.switchTab({
									url:"/pages/realtime/realtime"
								})
							}
						}
						break;
					case 5:
						this.status = "待评价"
						this.submitText='提交'
						this.submit = function(){
							this._ready()
						}
						break;
					case 6:
						this.status = "已结束"
						this.submitText='再次提交'
						this.isDetail=true;
						this.submit = function(){
							uni.redirectTo({
								url:"/pages/task/createTask"
							})
						}
						break;
					default:
						break;
				}
				this.map = uni.createMapContext("map1", this);
		}
			
		},
		computed: {
			rateSpan() {
				var rateSpan;
				if(this.rateValue<3){
					rateSpan="不满意"
				}else if(this.rateValue==3){
					rateSpan="一般"
				}else{
					rateSpan="满意"
				}
				return rateSpan
			}
		},
		onReady() {
			this._ready();
			console.log(getApp().globalData.basePath)
		}
	}
</script>

<style lang="scss">
	.detail_status{
		font-size: 40upx;
		&.red{
			color: #df1d1d;
		}
		&.blue{
			color: blue;
		}
		&.yellow{
			color: #d8df1d;
		}
		&.green{
			color: #22df1d;
		}
	}
	.content {
		width: 100%;
		box-sizing: border-box;
		padding: 0 20upx 112upx 20upx;
	}

	.detail_title_content {
		width: 100%;
		padding: 50upx 0upx 27upx 0upx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.detail_title_right {
		font-size: 32upx;
		font-weight: 100;
		letter-spacing: 0px;
	}

	.switch_content {
		display: flex;

		view {
			flex: 1;
			padding-left: 6.7upx;

			&:nth-child(1) {
				padding-left: 0;
			}
		}
	}

	.view_content {
		background-color: #fff;
		margin-top: -17.5upx;
		min-height: 300upx;
		color: #2ba3ef;
		padding: 40upx 14.5upx;
		box-sizing: border-box;
	}

	.span_content {
		margin-bottom: 35upx;
		font-size: 24upx;
		line-height: 40upx;
	}

	.map {
		width: 100%;
		height: 380upx;
		margin-top: 42upx;
		margin-bottom: 33upx;
	}

	.desc_scroll_content {
		width: 100%;
		height: 240upx;
		background-color: #ffffff;
		border-radius: 10upx;
		border: solid 1upx #2ba3ef;
		font-size: 24upx;
		color: #989898;
		line-height: 40upx;
		padding: 30upx 20upx;
		box-sizing: border-box;
		margin-top: 26upx;
		margin-bottom: 40upx;
	}

	.rate {
		margin: 27upx 0 40upx 0;
	}

	.bottom_content {
		position: fixed;
		bottom: 0;
		width: 100%;
		height: 112upx;
		border-top: 2upx solid #e5e5e5;
		background-color: #fff;
		margin-left: -22.5upx;
		box-sizing: content-box;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.rateSpan{
		font-size: 32upx;
		margin-left: 33upx;
	}
	.textarea{
		min-height: 100upx;
		margin-bottom: 40upx;
	}
	.image{
		width: 100%;
		height: 400upx;
	}
</style>
