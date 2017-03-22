<?php defined('IN_ECJIA') or exit('No permission resources.');?> 
<div class="panel panel-body">
	<div class="appeal-time-base m_b20">
		<ul>
			<li class="step-first">
				<div class="{if $check_status eq '-1'}step-cur{else}step-done{/if}">
					<div class="step-no">{if $check_status lt '1'}1{/if}</div>
					<div class="m_t5">提交申诉</div>	
				</div>
			</li>
			
			<li>
				<div class="{if $check_status eq '1'}step-cur{elseif $check_status gt '1'}step-done{/if}">
					<div class="step-no">{if $check_status lt '2'}2{/if}</div>
					<div class="m_t5">审核中</div>
				</div>
			</li>

			<li class="step-last">
				{if $check_status eq '2'}
				<div class="{if $check_status eq '2'}step-cur{/if}">
					<div class="step-no">{if $check_status lt '3'}3{/if}</div>
					<div class="m_t5">申诉成功</div>
				</div>
				{elseif $check_status eq '3' }
				<div class="{if $check_status eq '3'}step-cur{/if}">
					<div class="step-failed">{if $check_status lt '4'}3{/if}</div>
					<div class="m_t5">申诉失败</div>
				</div>
				{else}
				<div class="{if $check_status eq '2'}step-cur{/if}">
					<div class="step-no">{if $check_status lt '3'}3{/if}</div>
					<div class="m_t5">申诉成功</div>
				</div>
				{/if}
			</li>
		</ul>
	</div>
</div>
