<?php
/**
 * RSS输出
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */


session_start();

header('Content-type: application/xml; charset=utf-8');

$mysql = new SaeMysql();
$sql = "select id,userid,username,title,comment,update_date from `kindle_comments` where userid = (select userid from kindle_user where id = ".$_REQUEST['uid'].") order by update_date desc";
$data = $mysql->getData( $sql );

$URL = "http://kindlesync.sinaapp.com/comment/show_comment.php?id=";

echo '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
<title><![CDATA[Kindle摘录]]></title> 
<description><![CDATA[Kindle摘录]]></description>
<link>{$URL}</link>
<language>zh-cn</language>
<generator>www.emlog.net</generator>';

foreach($data as $value)
{
        $link = $URL.$value['id'];
        $abstract = $value['comment'];
        $pubdate =  gmdate('r',$value['update_date']);
        $author = $value['username'];
        echo <<< END
<item>
        <title>{$value['title']}</title>
        <link>$link</link>
        <description><![CDATA[{$abstract}]]></description>
        <pubDate>$pubdate</pubDate>
        <author>$author</author>
        <guid>$link</guid>

</item>
END;
}
echo <<< END
</channel>
</rss>
END;
