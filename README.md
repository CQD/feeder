# CQD 的 Feed 產生器

有些東西沒有 RSS Feed，但是我想訂閱，所以自己寫。

- 服務網址：https://feed.cqd.tw
- 程式碼：https://github.com/CQD/feeder

## Github

- /github/repo/{user}/{repo}/issuecomment
  - 某個 Repo 的所有 Issue/PR 的回應
  - 例如：https://feed.cqd.tw/github/repo/composer/composer/issuecomment

## Vocus

- /vocus/user/{id}
  - 某個作者的最新文章
  - 例如：https://feed.cqd.tw/vocus/user/sophist4ever
- /vocus/publication/{id}
  - 某個專題的最新文章
  - 例如：https://feed.cqd.tw/vocus/publication/sophist4ever

## 噗浪 Plurk

- /plurk/search/{keyword}
  - 用搜尋功能找到符合關鍵字的噗，僅包含公開噗
  - 範例
    - https://feed.cqd.tw/plurk/search/Love
    - https://feed.cqd.tw/plurk/search/%E7%B3%9F%E7%B3%95%E7%89%A9 (糟糕物)
