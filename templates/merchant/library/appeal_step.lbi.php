<?php defined('IN_ECJIA') or exit('No permission resources.');?> 
<div class="panel panel-body">
	<div class="appeal-time-base">
		<ul>
			<li class="step-first">
				<div class="{if $check_status eq '-1'}step-cur{else}step-done{/if}">
					<div class="step-no">{if $check_status lt '1'}1{/if}</div>
					<div class="m_t5">{t domain="comment"}提交申诉{/t}<br>{$appeal.appeal_time}</div>
				</div>
			</li>
			
			<li>
				<div class="{if $check_status eq '1'}step-cur{elseif $check_status gt '1'}step-done{/if}">
					<div class="step-no">{if $check_status lt '2'}2{/if}</div>
					<div class="m_t5">{t domain="comment"}审核中{/t}<br>{$appeal.appeal_time}</div>
				</div>
			</li>

			<li class="step-last">
				{if $check_status eq '2'}
				<div class="{if $check_status eq '2'}step-cur{/if}">
					<div class="step-no">{if $check_status lt '3'}3{/if}</div>
					<div class="m_t5">{t domain="comment"}申诉成功{/t}<br>{$appeal.process_time}</div>
				</div>
				{elseif $check_status eq '3' }
				<div class="{if $check_status eq '3'}step-cur{/if}">
					<div class="step-failed">{if $check_status lt '4'}3{/if}</div>
					<div class="m_t5">{t domain="comment"}申诉失败{/t}<br>{$appeal.process_time}</div>
				</div>
				{else}
				<div class="{if $check_status eq '2'}step-cur{/if}">
					<div class="step-no">{if $check_status lt '3'}3{/if}</div>
					<div class="m_t5">{t domain="comment"}申诉成功{/t}<br>{$appeal.process_time}</div>
				</div>
				{/if}
			</li>
		</ul>
	</div>
</div>
