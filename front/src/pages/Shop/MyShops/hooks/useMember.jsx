import { getApirUrl } from '../../../../helpers/getApirUrl.js';
import fetchApi from '../../../../helpers/fetchApi.js';
import { useCallback, useState } from 'react';
import { usePagination } from '../../../../hooks/usePagination.jsx';

const useMember = () => {
    const apiUrl = getApirUrl();
    const { page, onChangePage, setTotalPage, totalPage, setPage } =
        usePagination(0);
    const [member, setMember] = useState({});
    
    const [search, setSearchState] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [members, setMembers] = useState([]);
    
    const getMembers = useCallback(async () => {
        setIsLoading(true);
    
        console.log('search', search);
    
        const urlSearchParams = new URLSearchParams();
        urlSearchParams.append('itemsPerPage', 20);
        urlSearchParams.append('page', page + 1);
        urlSearchParams.append('firstname', search);
    
        const url = `${apiUrl}/users?${urlSearchParams.toString()}`;
        const response = await fetchApi(url);
        const data = await response.json();
        setMembers(data['hydra:member']);
        console.log(data);
        setTotalPage(Math.ceil(data['hydra:totalItems'] / 20));
        setIsLoading(false);
        return data;
    }, [apiUrl, page, setTotalPage, search]);
    
    const getMember = useCallback(
        async (id) => {
        setIsLoading(true);
        const url = `${apiUrl}/members/${id}`;
        const response = await fetchApi(url);
        const data = await response.json();
        setIsLoading(false);
        setMember(data);
        return data;
        },
        [apiUrl]
    );
    
    const setSearch = async (search) => {
        console.log('search', search);
        setSearchState(search);
        setPage(0);
    };
    
    return {
        isLoading,
        getMembers,
        members,
        page,
        onChangePage,
        totalPage,
        setSearch,
        getMember,
        member,
    };
}

export { useMember };
