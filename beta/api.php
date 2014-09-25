<?php require("header.php"); ?>

		<div class="body-content">
			<div class="contact-box" id="api-box">
				<div class="contact-headline">API</div>
				<div class="api-main-text">
					<p>消息输出格式:JSON</p>
					<p>HTTP Method:GET</p>
					<p>获取最新消息:</p>
					<p><strong>①http://news-at.zhihu.com/api/3/news/latest</strong></p>
					<p>获取消息内容:</p>
					<p><strong>②http://news-at.zhihu.com/api/3/news/+消息ID</strong></p>
					<p>消息ID可由①获得</p>
					<p>获取以往内容:</p>
					<p><strong>③http://news.at.zhihu.com/api/3/news/before/+日期</strong></p>
					<p>热门消息:</p>
					<p><strong>④http://news-at.zhihu.com/api/3/news/hot</strong></p>
				</div>
				<div class="contact-headline">Codes</div>
				<div class="api-main-text">
					<p>GitHub项目地址:<a href="https://github.com/leinue/daily" target="_blank">https://github.com/leinue/daily</a></p>
				</div>
			</div>
		</div>

<?php require("footer.php"); ?>