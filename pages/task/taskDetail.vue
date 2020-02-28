<template>
	<view class="" style="width: 100%;">
		<!-- <uni-nav-bar style="height:100upx" @clickLeft="back" left-icon="back" :title="taskInfo.name" backgroundColor="#1d94df"
		 :shadow="false" :border="false" fixed="true" color="#ffffff">
			<view slot="right">
				<span class="detail_status" :class="{'red':statusCode==6||statusCode==3,'yellow':statusCode==5,'blue':statusCode<3,'green':statusCode==4}">{{status}}</span>
			</view>
		</uni-nav-bar> -->
		<!-- <span class="detail_status" :class="{'red':statusCode==6||statusCode==3,'yellow':statusCode==5,'blue':statusCode<3,'green':statusCode==4}">{{status}}</span> -->
		<view class="content">
			<view class="detail_title_content">
				<view class="type_content flex flex_bottom">
					<button v-if="taskInfo.type == 'timeliness'" class="primary">时效性任务</button>
					<button v-if="taskInfo.type == 'cycle'" class="primary" @click="showList">周期任务</button>
					<button v-if="taskInfo.type == 'urgent'" class="primary" style="color: #e60012;">紧急任务</button>
					<uni-icons
						v-if="statusCode > 3 && userType != 'dronePilot'"
						type="paperplane"
						color="#fff"
						size="26"
						@click="taskShare"
						style="margin-left: 20upx;"
					></uni-icons>
				</view>
				<!-- <view @click="changeDate" v-if="taskInfo.type == 'cycle' && statusCode >= 4">
					<span style="text-decoration: underline;">{{ date }}</span>
				</view> -->
				<view class="detail_title_right" >
					<span>{{ taskInfo.startTime }}</span>
				</view>
			</view>
			<view class="switch_content">
				<view><button class="switch" :class="[action == 1 ? 'action' : '']" @click="active(1)">详情</button></view>
				<view v-if="taskInfo.type == 'timeliness' && statusCode > 3 || userType == 'dronePilot'"><button class="switch" :class="[action == 2 ? 'action' : '']" @click="active(2)">图片</button></view>
				<view v-if="taskInfo.type == 'cycle' && statusCode > 2 && userType != 'dronePilot'">
					<button class="switch" :class="[action == 5 ? 'action' : '']" @click="active(5)">违规判定</button>
				</view>
				<view v-if="taskInfo.type == 'timeliness' && statusCode > 3 || userType == 'dronePilot'"><button class="switch" :class="[action == 3 ? 'action' : '']" @click="active(3)">视频</button></view>
				<view v-if="taskInfo.type == 'cycle' && statusCode > 2"><button class="switch" :class="[action == 6 ? 'action' : '']" @click="active(6)">标准</button></view>
				<view v-if="taskInfo.type == 'timeliness' && statusCode > 3">
					<button class="switch" :class="[action == 4 ? 'action' : '']" @click="active(4)">沟通记录</button>
				</view>
			</view>
			<view class="view_content" id="view" ref="view" v-if="action == 1">
				<view class="span_content">
					<view class="span_label">
						<span>设备名称：</span>
						<span>{{ taskInfo.deviceName ||"--" }}</span>
					</view>
					<view class="span_label">
						<span>飞手名称：</span>
						<span>{{ taskInfo.personName || "--" }}</span>
					</view>
					<!-- <view class="span_label">
						<span>飞手位置：</span>
						<span>生态城</span>
					</view> -->
					<view class="span_label" v-if="taskInfo.type == 'cycle'" v-for="(item, index) in cycleTypeName" :key="index">
						<span>巡检周期：</span>
						<span>{{ item }}</span>
					</view>
				</view>
				<span class="title blue">任务地点</span>
				<map class="map" id="map1" v-show="mapShow" :latitude="latitude" :longitude="longitude" :markers="covers" :polyline="polyline"></map>
				<span class="title blue">任务描述</span>
				<scroll-view scroll-y class="desc_scroll_content">
					<p>{{ taskInfo.taskDesc }}</p>
				</scroll-view>
				<view v-if="statusCode < 4 && statusCode > 1">
					<span class="title blue">审批意见</span>
					<scroll-view scroll-y class="desc_scroll_content"><p>{{taskInfo.approveList[taskInfo.approveList.length-1].opinion}}</p></scroll-view>
				</view>
				<view v-if="statusCode >= 5 && taskInfo.type == 'timeliness' && userType != 'dronePilot'">
					<span class="title blue">满意度评价</span>
					<view class="rate" >
						<uni-rate :value="rateValue" :disabled="isDetail" @change="rateChange" :color="rateColor" :activeColor="rateActiveColor">
							<span class="rateSpan">{{ rateSpan }}</span>
						</uni-rate>
					</view>
					<textarea placeholder="请输入意见" class="textarea" auto-height v-if="statusCode == 5" v-model="taskstaisDesc" />
					<scroll-view scroll-y class="desc_scroll_content" v-if="statusCode == 6">
						<p>{{taskInfo.satisfactionDesc}}</p>
					</scroll-view>
				</view>
				<form v-if="userType != 'dronePilot' && taskInfo.type=='cycle'">
					<view class="iking-flex-left">
						<span class="title blue">任务标准</span>
						<button type="primary" size="mini" class="mini-btn" @click="uploadImage" style="background-color: #1d94df">提交</button>
					</view>
					<view class="uni-list list-pd">
						<view class="uni-list-cell cell-pd">
							<view class="uni-uploader">
								<view class="uni-uploader-head">
									<view class="uni-uploader-title">点击可预览选好的图片</view>
									<view class="uni-uploader-info">{{ imageList.length }}/9</view>
								</view>
								<view class="uni-uploader-body">
									<view class="uni-uploader__files">
										<block v-for="(image, index) in imageList" :key="index">
											<view class="uni-uploader__file"><image class="uni-uploader__img" :src="image" :data-src="image" @tap="previewImage"></image></view>
										</block>
										<view class="uni-uploader__input-box"><view class="uni-uploader__input" @tap="chooseImage"></view></view>
									</view>
								</view>
							</view>
						</view>
					</view>
				</form>
				<form v-if="userType == 'dronePilot' && statusCode > 3 && statusCode < 6">
					<view class="iking-flex-left">
						<span class="title blue">数据上传</span>
						<button type="primary" size="mini" class="mini-btn" @click="uploadImage" style="background-color: #1d94df">提交</button>
					</view>
					<view class="uni-list list-pd">
						<view class="uni-list-cell cell-pd">
							<view class="uni-uploader">
								<view class="uni-uploader-head">
									<view class="uni-uploader-title">点击可预览选好的图片</view>
									<view class="uni-uploader-info">{{ imageList.length }}/9</view>
								</view>
								<view class="uni-uploader-body">
									<view class="uni-uploader__files">
										<block v-for="(image, index) in imageList" :key="index">
											<view class="uni-uploader__file"><image class="uni-uploader__img" :src="image" :data-src="image" @tap="previewImage"></image></view>
										</block>
										<view class="uni-uploader__input-box"><view class="uni-uploader__input" @tap="chooseImage"></view></view>
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
			<scroll-view class="view_content" v-show="action == 2">
				<!-- <customSwiper :swiper-list="taskInfo.imageList" /> -->
				<view class="pic_list">
					<view @tap="clickPic(y)" v-for="(x, y) in taskInfo.imageList" :key="y"><image mode="aspectFill" lazy-load="@/static/headimage.png" :src="x.url"></image></view>
				</view>
				<view class="flex iking-flex-center" v-if="taskInfo.imageList.length==0">
					<span>暂无图片</span>
				</view>
				<!-- <img-preview @clickPic="closeImage" v-if="imageDetail" :picList="taskInfo.imageList" :current="current"></img-preview> -->
			</scroll-view>
			<scroll-view class="view_content" v-show="action == 5" scroll-y="true" >
				<swiper class="swiper">
					<swiper-item v-for="item in taskInfo.imageList">
						<view class="swiper-item flex iking-flex-center" style="background-color: #000;"><image :src="item.url" width="100%"></image></view>
					</swiper-item>
				</swiper>
				<video :id="'video' + index" style="width: 100%;" :src="item.url" :key="index" v-for="(item, index) in taskInfo.videoList" controls></video>
				<div class="list">
					<swiper class="swiper">
						<swiper-item v-for="item in taskInfo.referenceList">
							<view class="swiper-item flex iking-flex-center" style="background-color: #000;"><image :src="item.url" mode="aspectFit"></image></view>
						</swiper-item>
					</swiper>
				</div>
			</scroll-view>
			<scroll-view class="view_content" v-show="action == 6">
				<!-- <customSwiper :swiper-list="taskInfo.imageList" /> -->
				<view class="pic_list">
					<view @tap="clickRef(y)" v-for="(x, y) in taskInfo.referenceList" :key="y"><image mode="aspectFill" lazy-load="@/static/headimage.png" :src="x.url"></image></view>
				</view>
				<view class="flex iking-flex-center" v-if="taskInfo.referenceList.length==0">
					<span>暂无图片</span>
				</view>
			</scroll-view>
			<view class="view_content" v-if="action == 3">
				<video :id="'video' + index" style="width: 100%;" :src="item.url" :key="index" v-for="(item, index) in taskInfo.videoList" controls></video>
			</view>
			<view class="bottom_content" v-if="submitText!=''">
				<button class="primary blue" @click="submit">{{ submitText }}</button>
				<button class="primary blue" style="margin-left: 20upx;" @click="endTask" v-if="taskInfo.status == 'being'">结束任务</button>
			</view>
		</view>
		<uni-calendar class="calendar" ref="calendar" :insert="false" :date="date" :startDate="date" @confirm="dateConfirm" @close="dateClose" />
		<uni-drawer :visible="showLeft" mode="left" @close="closeDrawer()">
			<!-- #ifndef MP-BAIDU || MP-ALIPAY || MP-TOUTIAO -->
			<scroll-view scroll-y="true" style="height: 100%;">
				<uni-list>
					<uni-list-item :show-badge="true" :title="item.startTime" :badge-text="item.status" :key="index" @click="changeTask(item)" v-for="item,index in taskInfo.taskList"/>
				</uni-list>
			</scroll-view>
			<!-- #endif -->
			<view class="">
				<view class="word-btn" hover-class="word-btn--hover" :hover-start-time="20" :hover-stay-time="70" @click="hide"><text class="word-btn-white">关闭Drawer</text></view>
			</view>
		</uni-drawer>
	</view>
</template>

<script>
import uniRate from '@/components/uni-rate/uni-rate.vue';
import uniNavBar from '@/components/uni-nav-bar/uni-nav-bar.vue';
import customSwiper from '@/components/blackmonth-swiper/index';
import uniCalendar from '@/components/uni-calendar/uni-calendar.vue';
import uniIcons from '@/components/uni-icons/uni-icons.vue';
import uniDrawer from '@/components/uni-drawer/uni-drawer.vue'
import uniList from '@/components/uni-list/uni-list.vue'
import uniListItem from '@/components/uni-list-item/uni-list-item.vue'
// 查看图片详情（未使用）
import imgPreview from '@/components/img-preview/img-preview.vue';

const $beingBefore = '#2727fd';
const $being = '#22df1d';
const $wait = '#d8df1d';
const $finish = '#df1d1d';

function nowDate() {
	let nowDate = new Date();
	return nowDate.getFullYear() + '-' + ('0' + (nowDate.getMonth() + 1)).slice(-2) + '-' + ('0' + nowDate.getDate()).slice(-2);
}
var sourceType = [['camera'], ['album'], ['camera', 'album']];
var sizeType = [['compressed'], ['original'], ['compressed', 'original']];
export default {
	components: {
		uniRate,
		uniNavBar,
		customSwiper,
		uniCalendar,
		uniIcons,
		imgPreview,
		uniDrawer,
		uniList,
		uniListItem
	},
	data() {
		return {
			userType: this.$userInfo().userType,
			showLeft:false,
			mapShow:true,
			taskInfo: {
				pkId: 1,
				name: '--',
				personId: 1,
				personName: '--',
				personPhone: '--',
				deviceId: 1,
				deviceName: '--',
				startTime: '2020-01-01 12:00',
				endTime: '2020-01-01 13:00',
				type: 'timeliness',
				taskDesc: '',
				taskstaisDesc:"",
				status: 'being',
				flyTime: '10:00',
				mapType: 'area',
				mapList: [],
				cycleType: 'date',
				cycleList: [
					{
						id: 1,
						date: '1',
						week: '1',
						time: '9:00'
					},
					{
						id: 2,
						date: '2',
						week: '2',
						time: '12:00'
					},
					{
						id: 2,
						date: '3',
						week: '7',
						time: '13:00'
					}
				],
				approveList: [],
				imageList: [],
				videoList: [],
				referenceList: [],
				satisfaction: 0,
				satisfactionDesc: '描述'
			},
			date: nowDate(),
			status: '已结束',
			statusCode: 4,
			submitText: '提交',
			action: 1,
			latitude: 39.119929,
			longitude: 117.752705,
			rateColor: '#b5b5b5',
			rateActiveColor: '#1d94df',
			rateValue: 5,
			isDetail: false,
			video: null,
			src: null,
			imageList: [],
			imageDetail: false,
			current: 0,
			videoSrc: '',
			sourceTypeIndex: 1,
			sourceType: ['拍照', '相册', '拍照或相册'],
			sizeTypeIndex: 2,
			sizeType: ['压缩', '原图', '压缩或原图'],
			countIndex: 8,
			count: [1, 2, 3, 4, 5, 6, 7, 8, 9],
			covers: [],
			polyline: []
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
		back() {
			uni.navigateBack();
		},
		submit() {},
		showList(){
			this.showLeft = true;
			this.mapShow = false;
		},
		closeDrawer(){
			this.mapShow = true;
			this.showLeft = false;
		},
		changeTask(item){
			this.getTaskDetail(item.id).then(res=>{
				this._ready();
				this.showLeft = false;
			})
		},
		endTask() {
			uni.showModal({
				content: '是否结束任务？',
				showCancel: true,
				cancelText: '取消',
				confirmText: '确认',
				success: res => {
					if (res.confirm) {
						this.$request.post("/task/finishTask",{
							pkId:this.taskId
						}).then(res=>{
							this.getTaskDetail().then(res=>{
								this._ready();
							})
						})
					}
				}
			});
		},
		changeDate() {
			this.$refs.calendar.open();
		},
		dateConfirm() {},
		dateClose() {},
		taskShare() {
			uni.showModal({
				content: '是否分享该任务？',
				showCancel: true,
				cancelText: '取消',
				confirmText: '确认',
				success: res => {
					if(res.confirm){
						this.$request.post('/task/taskShare',{
							pkId:this.taskId
						}).then(res=>{
							if(res.flag==0){
								uni.showToast({
									title:"分享成功"
								})
							}else{
								uni.showToast({
									title:res.message,
									icon:"none"
								})
							}
						})
					}
				}
			});
		},
		clickPic(index) {
			// 				this.current = index;
			// 				this.imageDetail = true;
			uni.previewImage({
				urls: this.taskInfo.imageList.map(e => {
					return e.url;
				}),
				current: this.taskInfo.imageList.map(e => {
					return e.url;
				})[index]
			});
		},
		clickRef(index){
			uni.previewImage({
				urls: this.taskInfo.referenceList.map(e => {
					return e.url;
				}),
				current: this.taskInfo.referenceList.map(e => {
					return e.url;
				})[index]
			});
		},
		imageLoad(index){
			console.log(index)
		},
		closeImage() {
			this.imageDetail = false;
		},
		chooseImage: async function() {
			if (this.imageList.length === 9) {
				let isContinue = await this.isFullImg();
				console.log('是否继续?', isContinue);
				if (!isContinue) {
					return;
				}
			}
			uni.chooseImage({
				sourceType: sourceType[this.sourceTypeIndex],
				sizeType: sizeType[this.sizeTypeIndex],
				count: this.imageList.length + this.count[this.countIndex] > 9 ? 9 - this.imageList.length : this.count[this.countIndex],
				success: res => {
					this.imageList = this.imageList.concat(res.tempFilePaths);
				}
			});
		},
		isFullImg: function() {
			return new Promise(res => {
				uni.showModal({
					content: '已经有9张图片了,是否清空现有图片？',
					success: e => {
						if (e.confirm) {
							this.imageList = [];
							res(true);
						} else {
							res(false);
						}
					},
					fail: () => {
						res(false);
					}
				});
			});
		},
		uploadImage() {
			let imgs = this.imageList.map((value, index) => {
				return {
					name: 'image' + index,
					uri: value
				};
			});
			if (this.src) {
				imgs.push({
					name: 'video',
					uri: this.src
				});
			}
			var me = this;
			var url = '/files';
			if(this.userType=="demand"){
				url = '/files/ref'
			}
			this.$request.uploadFile(url,imgs, {
					taskId: this.taskInfo.pkId
			}).then(data=>{
				if (data.flag == '0') {
					me.imageList = [];
					this.getTaskDetail(this.taskInfo.pkId)
					uni.showToast({
						title: '上传成功'
					});
				}
			})
		},
		previewImage: function(e) {
			var current = e.target.dataset.src;
			uni.previewImage({
				current: current,
				urls: this.imageList
			});
		},
		chooseVideo: function() {
			uni.chooseVideo({
				sourceType: sourceType[this.sourceTypeIndex],
				success: res => {
					this.src = res.tempFilePath;
				}
			});
		},
		/**
		 * 修改导航栏buttons
		 * index[number] 修改的buttons 下标索引，最右边索引为0
		 * text[string] 需要修改的text 内容
		 */
		setStyle(index, text, color) {
			let pages = getCurrentPages();
			let page = pages[pages.length - 1];
			if (text.length > 3) {
				text = text.substr(0, 3) + '...';
			}
			// #ifdef APP-PLUS
			let currentWebview = page.$getAppWebview();
			let titleNView = currentWebview.getStyle().titleNView;
			// 添加文字过长截取为3个字符，请根据自己业务需求更改
			titleNView.buttons[index].text = text;
			titleNView.buttons[index].color = color;
			currentWebview.setStyle({
				titleNView: titleNView
			});
			// #endif
			// #ifdef H5
			// h5 临时方案
			document.getElementsByClassName('uni-btn-icon')[1].innerText = text;
			// #endif
		},
		getTaskDetail(id) {
			var me = this;
			return this.$request
				.post('/task/taskDetail', {
					pkId: id || this.taskId
				})
				.then(res => {
					let data = res;
					if (data.flag == 0) {
						let taskList = me.taskInfo.taskList;
						me.taskInfo = JSON.parse(JSON.stringify(data.data));
						// me.taskInfo.taskList.filter(val=>{
						// 	return val.id!=me.taskInfo.pkId
						// })
						me.taskInfo.taskList.pop();
						me.taskInfo.taskList.unshift({
							id:this.taskId,
							startTime:'当前任务'
						})
						if(id){
							me.taskInfo.taskList = taskList;
						}
						let mapList = JSON.parse(
							JSON.stringify(
								me.taskInfo.mapList.map(val => {
									val.latitude = val.lat;
									val.longitude = val.lng;
									return val;
								})
							)
						);
						if (me.taskInfo.mapType == 'line') {
							me.covers = mapList;
						}
						me.latitude = me.taskInfo.mapList[0].lat;
						me.longitude = me.taskInfo.mapList[0].lng;
						me.polyline.push({
							points: mapList
						});
					}
					return data
				});
		},
		_ready() {
			this.video = uni.createVideoContext('video1', this);
			console.log(this.taskInfo.status);
			switch (this.taskInfo.status) {
				case 'waitApprove':
					this.status = '待审批';
					this.statusCode = 1;
					this.submitText = '关闭';
					this.titleColor = $beingBefore;
					this.submit = function() {
						uni.navigateBack();
					};
					break;
				case 'waitStart':
					this.status = '通过';
					this.statusCode = 2;
					this.submitText = '开始任务';
					this.titleColor = $beingBefore;
					this.submit = function() {
						uni.showModal({
							content: '是否开始任务？',
							showCancel: true,
							cancelText: '取消',
							confirmText: '确认',
							success: res => {
								if (res.confirm) {
									this.$request
										.post('/task/startTask', {
											pkId: this.taskId
										})
										.then(res => {
											console.log(res);
											if (res.flag == 0) {
												this.getTaskDetail().then(res => {
													this._ready();
												});
											}
										});
								}
							}
						});
					};
					break;
				case 'noApprove':
					this.status = '拒绝';
					this.statusCode = 3;
					this.submitText = '修改';
					this.titleColor = $finish;
					this.submit = function() {
						uni.redirectTo({
							url: `/pages/task/createTask?taskId=${this.taskId}&type=revision`
						});
					};
					break;
				case 'being':
					this.status = '执行中';
					this.statusCode = 4;
					this.titleColor = $being;
					if (this.userType == 'dronePilot') {
						console.log(this.taskInfo)
						let status = '';
						var me = this;
						this.taskInfo.taskList.forEach(item=>{
							(item.status == 'being'&&item.id!=me.taskId)?status='being':''
						})
						this.submitText = '';
						if(this.taskInfo.type=="cycle"&&this.taskInfo.pkId==this.taskInfo.taskId&&status!='being'){
							this.submitText = '开始任务';
							this.submit = function() {
							uni.showModal({
								content: '是否开始任务？',
								showCancel: true,
								cancelText: '取消',
								confirmText: '确认',
								success: res => {
									if (res.confirm) {
										this.$request
											.post('/task/startTask', {
												pkId: this.taskId
											})
											.then(res => {
												console.log(res);
												if (res.flag == 0) {
													this.getTaskDetail(res.data.id).then(res => {
														this._ready();
													});
												}
											});
									}
								}
							});
						};
						}else if(this.taskInfo.type=="cycle"&&this.taskInfo.pkId==this.taskInfo.taskId){
							this.submitText = '';
						}
						else{
							this.submitText = '任务完成';
							this.submit = function() {
								uni.showModal({
									content: '任务是否完成？',
									showCancel: true,
									cancelText: '取消',
									confirmText: '确认',
									success: res => {
										if(res.confirm){
											this.$request.post("/task/successTask",{
												pkId:this.taskInfo.pkId
											}).then(res=>{
												if(res.flag==0){
													uni.showToast({
														title:"操作成功",
														icon:"none"
													})
													this.getTaskDetail().then(res=>{
														this._ready()
													})
												}else{
													uni.showToast({
														title:"操作失败",
														icon:"none"
													})
												}
											})
										}
									}
								});
							};
							
						}
						
					} else {
						this.submitText = '实时';
						this.submit = function() {
							uni.switchTab({
								url: '/pages/realtime/realtime'
							});
						};
					}
					break;
				case 'waitEval':
					this.status = '待评价';
					this.statusCode = 5;
					this.submitText = '提交';
					this.titleColor = $wait;
					if (this.userType == 'dronePilot') {
						this.submitText = '';
						return
					}
					this.submit = function() {
						uni.showModal({
							content: '是否确定提交？',
							showCancel: true,
							cancelText: '取消',
							confirmText: '确认',
							success: res => {
								if (res.confirm) {
									this.$request
										.post('/task/applySatisfaction', {
											pkId: this.taskId,
											rateNum:this.rateValue,
											opinion:this.taskstaisDesc
										})
										.then(res => {
											console.log(res);
											if (res.flag == 0) {
												this.getTaskDetail().then(res => {
													this._ready();
												});
											}
										});
								}
							}
						});
					};
					break;
				case 'finish':
					this.status = '已结束';
					this.statusCode = 6;
					this.submitText = '再次提交';
					this.titleColor = $finish;
					this.isDetail = true;
					if(this.userType=="dronePilot"){
						this.submitText = ''
					}
					
					this.submit = function() {
						uni.redirectTo({
							url: `/pages/task/createTask?taskId=${this.taskId}&type=restart`
						});
					};
					break;
				default:
					break;
			}
			this.setStyle(0, this.status, this.titleColor);
			uni.setNavigationBarTitle({
				title: this.taskInfo.name
			});
			this.map = uni.createMapContext('map1', this);
		}
	},
	computed: {
		rateSpan() {
			var rateSpan;
			if (this.rateValue < 3) {
				rateSpan = '不满意';
			} else if (this.rateValue == 3) {
				rateSpan = '一般';
			} else {
				rateSpan = '满意';
			}
			return rateSpan;
		},
		cycleTypeName() {
			let name = [];
			var me = this;
			this.taskInfo.cycleList.forEach(function(item) {
				switch (me.taskInfo.cycleType) {
					case 'month':
						name.push(`每月 ${item.date}日 ${item.time}`);
						break;
					case 'week':
						var weekList = ['一', '二', '三', '四', '五', '六', '日'];
						name.push(`每周${weekList[item.week - 1]} ${item.time}`);
						break;
					case 'date':
						name.push(`每日 ${item.time}`);
						break;
				}
			});
			return name;
		}
	},
	onLoad(option) {
		var me = this;
		console.log('load');
		this.taskId = option.taskId;
		this.getTaskDetail().then(res=>{
			this._ready();
		});
	},
	onReady() {
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
				console.log('success');
			},
			fail(e) {
				console.log('fail', JSON.stringify(e));
			}
		});
	}
};
</script>

<style lang="scss">
.detail_status {
	// position: absolute;
	// // top: 10upx;
	// top:-56upx;
	// right: 33.5upx;
	// z-index: 1000;
	// font-family: ikingFont;
	font-size: 40upx;
	&.red {
		color: #df1d1d;
	}
	&.blue {
		color: blue;
	}
	&.yellow {
		color: #d8df1d;
	}
	&.green {
		color: #22df1d;
	}
}
.content {
	width: 100%;
	box-sizing: border-box;
	padding: 0 20upx 112upx 20upx;
}
.swiper{
	margin: 20upx 0;
	height: 350upx;
	background-color: #000;
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
	margin: 27upx 0 40upx 0 !important;
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
.rateSpan {
	font-size: 32upx;
	margin-left: 33upx;
}
.textarea {
	min-height: 100upx;
	margin-bottom: 40upx;
}
.image {
	width: 100%;
	height: 400upx;
}
.pic_list {
	display: flex;
	flex-flow: row wrap;
	> view {
		flex: 0 0 220rpx;
		height: 220rpx;
		margin: 7.5rpx 0 0 7.5rpx;
		> image {
			width: 100%;
			height: 100%;
		}
	}
}
</style>
