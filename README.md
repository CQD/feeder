# CQD 的 Feed 產生器

有些東西沒有 RSS Feed，但是我想訂閱，所以自己寫。

- 服務網址：https://feed.cqd.tw
- 程式碼：https://github.com/CQD/feeder

## PTT
- /ptt/{看板_ID}/title/{regex}
  - 看板文章依照 regex 過濾標題，無視大小寫
  - 範例：https://feed.cqd.tw/ptt/Gamesale/title/PS5.%2A%E4%B8%BB%E6%A9%9F (Gamesale 板標題符合 `PS5.*主機` 的文章)

另，PTT 內建有
- 看板 ATOM feed https://www.ptt.cc/atom/{看板_ID}.xml

## Github

- /github/repo/{user}/{repo}/issuecomment
  - 某個 Repo 的所有 Issue/PR 的回應
  - 範例：https://feed.cqd.tw/github/repo/composer/composer/issuecomment

## Vocus

- /vocus/user/{id}
  - 某個作者的最新文章
  - 例如：https://feed.cqd.tw/vocus/user/sophist4ever
- /vocus/publication/{id}
  - 某個專題的最新文章
  - 範例：https://feed.cqd.tw/vocus/publication/sophist4ever

## 噗浪 Plurk

- /plurk/search/{keyword}
  - 用搜尋功能找到符合關鍵字的噗，僅包含公開噗
  - 範例
    - https://feed.cqd.tw/plurk/search/Love
    - https://feed.cqd.tw/plurk/search/%E6%84%9B%E6%83%85 (愛情)

另，噗浪內建有
- 使用者的公開噗 https://www.plurk.com/{nick_name}.xml

## 台灣電力企業聯合會

- /tepa/epaper
  - 台灣電力企業聯合會電子報
  - 範例：https://feed.cqd.tw/tepa/epaper

## 591
- /591/comu/{建案id}
  - 指定建案的最近上架物件
  - 範例：https://feed.cqd.tw/591/comu/4055