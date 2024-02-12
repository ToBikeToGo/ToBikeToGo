import { useState } from 'react';

const usePagination = (initialPage = 1) => {
  const [page, setPage] = useState(1);
  const [totalPage, setTotalPage] = useState(1);

  const onChangePage = (event, page) => {
    setPage(page);
  };

  const pagination = {
    page,
    totalPage,
    onChangePage,
    setTotalPage,
  };

  return pagination;
};

export { usePagination };
