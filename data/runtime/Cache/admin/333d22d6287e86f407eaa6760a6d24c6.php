<?php if (!defined('THINK_PATH')) exit();?><div class="dialog_content pad_10"><div class="loading">		共有采集器[ <b class="red"><?php echo ($robots_count); ?></b> ] 个， 当前第[ <b class="red"><?php echo ($rids); ?></b> ]个采集器使用 <?php if($date['http_mode'] == 0): ?>API采集 <?php else: ?> 淘宝网采集<?php endif; ?><br />		正在采集：【<?php echo ($date["name"]); ?>】,共[ <b class="red"><?php echo ($date["page"]); ?></b> ]页,正在采集第 [ <b class="blue"><?php echo ($result_data["p"]); ?></b> ] 页，<br />		本页共查询到 <?php echo ($result_data["totalnum"]); ?> 条数据，已成功入库 【<span class="blue"><?php echo ($result_data["thiscount"]); ?></span> 】个商品
	</div><?php if(!empty($msg)): endif; ?><center>【<?php echo ($date["name"]); ?>】分类本次共采集  <?php echo ($result_data['thiscount']*$result_data['p']);?>个商品</center><div align="center"><font color="red">采集中请勿关闭本窗口...</font></div></div>