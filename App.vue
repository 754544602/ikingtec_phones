<script>
	export default {
		onLaunch: function() {
			const value = JSON.parse(uni.getStorageSync('login_info'))
			if (value.login_state == "true") {
				this.$store.state.hasLogin = true;
				console.log(this.$store)
				this.$store.state.userName = value.username;
			}
			console.log('App Launch');
		},
		onShow: function() {
			console.log('App Show');
		},
		onHide: function() {
			console.log('App Hide');
		},
		globalData: {  
			basePath:"localhost:8080/"
        }
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import 'uni.scss';
	@import 'common/uni.scss';

	@font-face {
		font-family: ikingFont;
		font-weight: normal;
		font-style: normal;
		src: url('./static/font/SourceHanSansCN-Bold.otf') format('truetype');
	}

	page {
		font-family: ikingFont;
		min-height: 100%;
		display: flex;
		background-image: linear-gradient($iking-color-blue 5%, $iking-color-common 45%);
		/* #ifndef APP-NVUE */
		background-attachment: fixed;
		/* #endif */
		color: $iking-color-common;
		box-sizing: border-box;
	}

	.ikingtec_h1 {
		font-size: 53upx;
		font-weight: 700;
		margin-top: 47upx;
	}

	.title {
		font-size: 40upx;
		font-weight: 700;

		&.blue {
			color: $iking-color-blue;
		}
	}

	.content {
		display: flex;
		flex: 1;
		flex-direction: column;
		padding: 47upx 45upx;
	}

	button {
		&.primary {
			background-color: #fff;
			color: $iking-color-blue;
			font-size: 40upx;
			line-height: 40upx;
			padding: 23upx 48upx;
			margin: 0;
			font-weight: 700;
			border-radius: 15upx;
		}

		&.blue {
			background-color: $iking-color-blue;
			color: $iking-color-common;
		}
		
		&.switch{
			background-color: #73a7c7;
			border-radius: 7upx 7upx 0 0 ;
			color: $iking-color-common;
			font-size: 33.5upx;
			padding: 0;
			&:after{
				border: none;
			}
			&.action{
				background-color: #1d94df;
			}
		}
	}

	.textarea {
		background-color: #fff;
		border: 2upx solid $iking-color-blue;
		border-radius: 10upx;
		width: 100%;
		min-height: 400upx;
		color: #989898;
		padding: 20upx 40upx;
		box-sizing: border-box;
		font-size: 24upx;
	}
	.primary{
		&:after{
			border: none;
		}
		&.action{
			color:$iking-color-common;
			background-color: $iking-color-blue;
			box-shadow: 1upx 2px 6upx 0.5upx rgba(0, 0, 0, 0.43);
		}
		&.blue.date{
			width: 100%;
			font-size: 32upx;
			margin-top: 33upx;
			margin-bottom: 41upx;
		}
	}
	.type_content button{
		width:208upx;
		padding:27upx 24upx;
		font-size: 32upx;
	}
</style>
