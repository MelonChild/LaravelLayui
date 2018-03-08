//设置cookie
function setCookie(cname, cvalue, exdays)
{
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

//获取cookie
function getCookie(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++)
    {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}

$(function () {
    //提交搜索
    $('.dont-touch-my-class-search').click(function () {
        var url = "?";
        var search = $('[name="search"]').val();
        search && (url += "search=" + search);
        typeof $('#searchSelect').val() != 'undefined'  && $('#searchSelect').val() && (url += (search ? '&' : '') + "selectId=" + $('#searchSelect').val());
        console.log($('#searchSelect').val(),url);
        location.href = url;
    });
})
