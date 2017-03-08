<?php defined('IN_ECJIA') or exit('No permission resources.');?> 

<div class="panel panel-body">
	<div class="appeal-time-base m_b20">
		<ul>
			<li class="step-first">
				<div class="{if $check_status eq '-1'}step-cur{else}step-done{/if}">
					<div class="step-no">{if $check_status eq '-1'}1{/if}</div>
					<div class="m_t5">提交申诉</div>	
				</div>
			</li>
			<li>
				<div class="{if $step eq '2'}step-cur{elseif $step gt '2'}step-done{/if}">
					<div class="step-no">{if $step lt '3'}2{/if}</div>
					<div class="m_t5">审核中</div>
				</div>
			</li>

			<li class="step-last">
				<div class="{if $step eq '3'}step-cur{/if}">
					<div class="step-no">{if $step lt '5'}3{/if}</div>
					<div class="m_t5">申诉成功</div>
				</div>
			</li>
		</ul>
	</div>
</div>
