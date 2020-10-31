jQuery(function() {
    $('.poll').each(()=>{
        $(this).find('.poll-options').each(()=>{
            console.log($(this))
        })
    })
})
