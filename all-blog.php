<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمام مقالات - Pixhom</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/navbar.html'; ?>

    <div class="container" style="padding-top: 100px; padding-bottom: 60px;">
        <h1 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #ec4899 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">تمام مقالات</h1>

        <div id="allBlogGrid" class="blog-grid"></div>
    </div>

    <?php include 'components/footer.html'; ?>

    <script>
        let blogData = [];

        async function loadBlogData() {
            try {
                const response = await fetch("api/get-blog.php");
                const data = await response.json();
                blogData = data.map((item) => ({
                    id: item.id,
                    title: item.title,
                    date: new Date(item.created_at).toLocaleDateString("fa-IR"),
                    excerpt: item.excerpt,
                    link: `/blog-post.php?id=${item.id}`,
                }));
                renderAllBlog();
            } catch (error) {
                console.log("[v0] Error loading blog:", error);
            }
        }

        function renderAllBlog() {
            const grid = document.getElementById('allBlogGrid');
            grid.innerHTML = '';

            blogData.forEach((post) => {
                const html = `
                    <div class="blog-post" onclick="location.href='${post.link}'">
                        <div class="blog-post-image">📰</div>
                        <div class="blog-post-content">
                            <div class="blog-post-date">${post.date}</div>
                            <h3 class="blog-post-title">${post.title}</h3>
                            <p class="blog-post-excerpt">${post.excerpt}</p>
                        </div>
                    </div>
                `;
                grid.innerHTML += html;
            });
        }

        document.addEventListener("DOMContentLoaded", loadBlogData);
    </script>
</body>
</html>
