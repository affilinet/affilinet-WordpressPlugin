<style type="text/css" scoped="scoped">
    html {
        background-color: #ffffff !important;
    }

    #startIframe {
        height: 3500px;
        overflow: hidden;
        max-width: 768px;
        width: 100%;
        min-width: 1300px;
        margin-left: -20px;
    }

    @media screen and (max-width: 782px) {
        #startIframe {
            margin-left: -10px;
            min-width: inherit;
        }
    }
</style>
<iframe id="startIframe" src="<?php echo __('https://www.affili.net/htmlcontent/en/Publishermodules/WordPress/Welcome.html', 'affilinet-performance-module'); ?>" style=""></iframe>
