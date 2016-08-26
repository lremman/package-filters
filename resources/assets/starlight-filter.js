(function($, undefined) {

    window.StarlightFilter = function(){

        var $this = $(this);

        // Updating Filter
        var updateFilter = function (data){
            $this.trigger('starlight-filter:updateFilter', [data.viewData]);
            $.each(data.filterElements, function(key, filterElement){
                var $select = $this.closest('form').find('[name=' + key + ']');
                $this.trigger('starlight-filter:updateSelect', [$select, filterElement.items]);
            });
        };

        $this.on('change', function(e){

            var $form = $this.closest('form');

            var url = $this.data('load-children') + '?filterElement=' + $this.attr('name');

            $.ajax(
            {
                url : url,
                type: "GET",
                data : $form.serialize(),
                beforeSend: function() {
                    $this.trigger('starlight-filter:beforeAjax', $this);
                },
                complete: function(){
                    $this.trigger('starlight-filter:afterAjax', $this);
                },
                success:function(data)
                {
                    updateFilter(data);
                },
                error: function()
                {
                    console.error('Error updating starlightFilter')
                }
            });
        });
    };

    $('.starlight-filter').each(window.StarlightFilter);

})(jQuery);
