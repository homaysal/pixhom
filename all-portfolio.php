<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمام نمونه کارها - Pixhom</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/navbar.html'; ?>

    <div class="container" style="padding-top: 100px; padding-bottom: 60px;">
        <h1 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #ec4899 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">تمام نمونه کارها</h1>

        <!-- فیلتر دسته‌بندی -->
        <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 2rem; flex-wrap: wrap;">
            <button class="filter-btn active" onclick="filterPortfolio('all')">همه</button>
            <button class="filter-btn" onclick="filterPortfolio('template')">قالب</button>
            <button class="filter-btn" onclick="filterPortfolio('plugin')">پلاگین</button>
            <button class="filter-btn" onclick="filterPortfolio('tool')">ابزار</button>
        </div>

        <div id="allPortfolioGrid" class="portfolio-grid"></div>
    </div>

    <?php include 'components/footer.html'; ?>

    <script>
        let portfolioData = [];
        let initialFilter = 'all';

        // بررسی URL parameter برای فیلتر اولیه
        const urlParams = new URLSearchParams(window.location.search);
        const categoryParam = urlParams.get('category');
        if (categoryParam && categoryParam !== 'all') {
            initialFilter = categoryParam;
        }

        async function loadPortfolioData() {
            try {
                const response = await fetch("api/get-portfolio.php");
                const data = await response.json();
                portfolioData = data.map((item) => ({
                    id: item.id,
                    title: item.title,
                    category: item.category,
                    description: item.description,
                    preview_text: item.preview_text || item.description,
                    image: item.image_path || "🎨",
                    link: `/portfolio-detail.php?id=${item.id}`,
                }));
                filterPortfolio(initialFilter);
            } catch (error) {
                console.log("[v0] Error loading portfolio:", error);
            }
        }

        function filterPortfolio(category) {
            const grid = document.getElementById('allPortfolioGrid');
            grid.innerHTML = '';

            const filtered = category === 'all' 
                ? portfolioData 
                : portfolioData.filter(item => item.category === category);

            if (filtered.length === 0) {
                grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: var(--text-secondary);">هیچ نمونه کاری در این دسته‌بندی یافت نشد.</div>';
                return;
            }

            filtered.forEach((item) => {
                const imageHtml =
                    item.image && item.image !== "🎨"
                        ? `<img src="${item.image}" alt="${item.title}" style="width: 100%; height: 100%; object-fit: cover;">`
                        : `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem;">🎨</div>`;

                const html = `
                    <div class="portfolio-item" data-category="${item.category}" onclick="location.href='${item.link}'">
                        <div class="portfolio-item-image" style="position: relative;">
                            ${imageHtml}
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 1rem; color: white;">
                                <h4 style="margin: 0; font-size: 0.9rem;">${item.title}</h4>
                            </div>
                        </div>
                        <div class="portfolio-item-content">
                            <div class="portfolio-item-category">${item.category}</div>
                            <p class="portfolio-item-description">${item.preview_text.replace(/\n/g, '<br>')}</p>
                            <a href="${item.link}" class="portfolio-item-link">مشاهده جزئیات →</a>
                        </div>
                    </div>
                `;
                grid.innerHTML += html;
            });

            // به‌روزرسانی دکمه‌های فعال
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
                const btnText = btn.textContent;
                const isMatch = (category === 'all' && btnText === 'همه') ||
                               (category === 'template' && btnText === 'قالب') ||
                               (category === 'plugin' && btnText === 'پلاگین') ||
                               (category === 'tool' && btnText === 'ابزار');
                if (isMatch) {
                    btn.classList.add('active');
                }
            });
        }

        document.addEventListener("DOMContentLoaded", loadPortfolioData);
    </script>
</body>
</html>
