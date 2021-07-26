<?php
global $默认侧边栏MD路径;
$默认侧边栏MD路径 = "./static/defaultpage/_sidebar.md";

function 输出分类列表($markdown文件夹)
{
    $分类列表 = scandir($markdown文件夹);
    // markdown文件夹下第一级为 文章分类名
    foreach ($分类列表 as $分类名) {
        // 过滤掉./和../路径
        if ($分类名 !== "." && $分类名 !== "..") {
            $路径 = $markdown文件夹 . "/" . $分类名;
            if (is_dir($路径)) {
                输出文章分类($分类名, $路径);
            }
        }
    }
}
function 输出文章分类($分类名, $路径)
{
    file_put_contents($GLOBALS['默认侧边栏MD路径'], "* " . $分类名 . "\r\n", FILE_APPEND);
    输出分类下文章列表($分类名,$路径);
}
function 输出分类下文章列表($分类名,$路径)
{
    // 输出文件夹中的MD文件元素
    $文件列表 = scandir($路径);
    foreach ($文件列表 as $文件) {
        if ($文件 !== "." && $文件 !== ".." && pathinfo($文件, PATHINFO_EXTENSION) === "md") {
            $文件真实路径 = $分类名 . "/" . $文件;
            file_put_contents($GLOBALS['默认侧边栏MD路径'], "    * [" . $文件 . "](" . $文件真实路径 . ")\r\n", FILE_APPEND);
        }

    }
}

// 先删除文件
unlink($默认侧边栏MD路径);
// 重新生成侧边栏文件
输出分类列表("./markdown");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="icon" href="/static/favicon.ico" type="image/x-icon" />
    <title>retrocode</title>
    <meta name="ShowMeBaby's Blog" content="v1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="description" content="Description">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/docsify/lib/themes/vue.css">
  </head>
  <body>
    <div id="app">加载中，请稍后...</div>
    <script>
      var num = 0;
      window.$docsify = {
        coverpage: '../static/defaultpage/_coverpage.md', // 启用封面页。开启后是加载 _coverpage.md 文件，也可以自定义文件名。
        requestHeaders: {
          'cache-control': 'max-age=600',
        }, // 设置请求资源的请求头。
        notFoundPage: '../static/defaultpage/_404.md', // 在找不到指定页面时加载_404.md
        routerMode: 'hash', // default: 'hash'/'history'
        name: 'ShowMeBaby', // 文档标题，会显示在侧边栏顶部。name 项也可以包含自定义 HTML 代码来方便地定制(name: '<span>docsify</span>')
        noEmoji: false, // 禁用 emoji 解析。
        logo: '../static/logo.svg', // 在侧边栏中出现的网站图标，你可以使用CSS来更改大小
        basePath: '/markdown/', // 文档加载的根路径，可以是二级路径或者是其他域名的路径。
        homepage: '/ABOUT/AboutMe.md', // 设置首页文件加载路径。适合不想将 README.md 作为入口文件渲染，或者是文档存放在其他位置的情况使用。
        loadSidebar: '../static/defaultpage/_sidebar.md', // 加载自定义侧边栏，设置为 true 后会加载 _sidebar.md 文件，也可以自定义加载的文件名。
        autoHeader: true, // 同时设置 loadSidebar 和 autoHeader 后，可以根据 _sidebar.md 的内容自动为每个页面增加标题。
        subMaxLevel: 10, // 自定义侧边栏后默认不会再生成目录，你也可以通过设置生成目录的最大层级开启这个功能。
        auto2top: true, // 切换页面后是否自动跳转到页面顶部。
        repo: 'https://github.com/ShowMeBaby', // 配置仓库地址或者 username/repo 的字符串，会在页面右上角渲染一个 GitHub Corner 挂件。
        mergeNavbar: true, // 小屏设备下合并导航栏到侧边栏。
        formatUpdated: '{YYYY}/{MM}/{DD} {HH}:{mm}:{ss}', // 我们可以通过 {docsify-updated} 变量显示文档更新日期. 并且通过 formatUpdated配置日期格式。
        // 配置全文搜索参数
        search: {
          maxAge: 86400000, // 过期时间，单位毫秒，默认一天
          paths: 'auto', // 搜索路径默认auto全部即可
          placeholder: '全文搜索',
          noData: '找不到结果',
          depth: 6, // 搜索标题的最大层级, 1 - 6
        },
        // 配置复制代码功能
        copyCode: {
          buttonText: '点击复制',
          errorText: '复制成功',
          successText: '复制失败'
        },
        // 配置分页插件
        pagination: {
          previousText: '上一章节',
          nextText: '下一章节',
        },
        // 配置字数统计插件
        count: {
          countable: true, // 设置字符统计展示与否(默认值Default) / false
          position: 'top', // 设置展示位置 'top'(默认值Default) / 'bottom'
          margin: '10px', // 设置与邻近DOM的距离 '10px'
          float: 'top', // 设置元素对齐 'right'(默认值Default) / 'top'
          fontsize: '1rem', // 设置字体大小
          color: 'rgb(90,90,90)', // 设置颜色
          language: 'chinese', // 设置语言 'english'(默认值Default) / 'chinese'
          isExpected: true // 是否显示预计阅读时长	true(默认值Default) / false
        },
        // 配置markdown解析
        markdown: {
          renderer: {
            code: function (code, lang) {
              if (lang === "mermaid") {
                return ('<div class="mermaid">' + mermaid.render('mermaid-svg-' + num++, code) + "</div>");
              }
              return this.origin.code.apply(this, arguments);
            }
          }
        },
        // 配置备案信息
        beian: {
          ICP: "陕ICP备18016129号-3",
          NISMSP: {
              number: "",
              url: "",
              id: ""
          }
        }
      }
    </script>
    <script src="//cdn.jsdelivr.net/npm/docsify/lib/docsify.min.js"></script>
    <!-- 全文搜索插件会根据当前页面上的超链接获取文档内容，在 localStorage 内建立文档索引。默认过期时间为一天，当然我们可以自己指定需要缓存的文件列表或者配置过期时间。 -->
    <script src="//cdn.jsdelivr.net/npm/docsify/lib/plugins/search.min.js"></script>
    <!-- Medium's 风格的图片缩放插件. 基于 medium-zoom。 -->
    <script src="//cdn.jsdelivr.net/npm/docsify/lib/plugins/zoom-image.min.js"></script>
    <!-- 默认是提供 emoji 解析的，能将类似 :100: 解析成 100。但是它不是精准的，因为没有处理非 emoji 的字符串。如果你需要正确解析 emoji 字符串，你可以引入这个插件。 -->
    <script src="//cdn.jsdelivr.net/npm/docsify/lib/plugins/emoji.min.js"></script>
    <!-- 在 docsify 中添加备案信息的插件 -->
    <script src ="//cdn.jsdelivr.net/npm/docsify-beian@latest/dist/beian.min.js "></script>
    <!-- 在所有的代码块上添加一个简单的Click to copy按钮来允许用户从你的文档中轻易地复制代码。由@jperasmus提供。 -->
    <script src="//cdn.jsdelivr.net/npm/docsify-copy-code"></script>
    <!-- docsify的分页导航插件，由@imyelo提供。 -->
    <script src="//cdn.jsdelivr.net/npm/docsify-pagination/dist/docsify-pagination.min.js"></script>
    <!-- 它提供了统计中文汉字和英文单词的功能，并且排除了一些markdown语法的特殊字符例如*、-等 -->
    <script src="//cdn.jsdelivr.net/npm/docsify-count/dist/countable.min.js"></script>
    <!-- 内置的代码高亮工具是 Prism，默认支持 CSS、JavaScript 和 HTML。如果需要高亮其他语言，例如PHP，可以手动引入代码高亮插件。 -->
    <script src="//cdn.jsdelivr.net/npm/prismjs/components/prism-java.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/prismjs/components/prism-markdown.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/prismjs/components/prism-yaml.min.js"></script>
    <!-- 一个用于呈现LaTex数学方程式的 docsify插件。 -->
    <script src="//cdn.jsdelivr.net/npm/docsify-katex@latest/dist/docsify-katex.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/katex@latest/dist/katex.min.css" />
    <!-- 一个用于呈现美人鱼图的 docsify插件。 -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.css">
    <script src="//cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script type="text/javascript">
      mermaid.initialize({
        startOnLoad: false
      });
    </script>
  </body>
</html>
