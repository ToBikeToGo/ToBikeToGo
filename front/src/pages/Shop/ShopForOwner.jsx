import { ShopWithDetails } from './components/ShopWithDetails.jsx';
import { useShopContext } from '../../hooks/UseShop.jsx';
import { useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import { CircularProgress, useTheme } from '@mui/material';
import { MemberTable } from '../../components/Member/MemberTable.jsx';

export const ShopForOwner = () => {
  const { getShopWithMembers, shop, members } = useShopContext();
  const theme = useTheme();
  const { shopId } = useParams();

  useEffect(() => {
    getShopWithMembers(shopId);
  }, [getShopWithMembers]);

  if (!shop?.users) {
    return <CircularProgress sx={{ m: 5 }} />;
  }
  return (
    <Box
      sx={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
      }}
    >
      <ShopWithDetails shop={shop} canEdit={true} />
      <Button variant="contained" href={`/shop/${shopId}/edit`}>
        Edit
      </Button>
      <Button
        variant="contained"
        href={`/shop/${shopId}/members`}
        color={'black'}
        sx={{ mt: 2, color: theme.palette.common.white }}
      >
        Manage members
      </Button>
      <Box sx={{ width: '80%', marginTop: '2em' }}>
        <MemberTable
          members={members}
          onMemberDelete={() => {}}
          onMemberEdit={() => {}}
        />
      </Box>
      <Button
        variant="contained"
        component={Link}
        to={`/my-shops/add-member/${shopId}`}
        color={'black'}
        sx={{ mt: 2, color: theme.palette.common.white }}
      >
        +
      </Button>
    </Box>
  );
};
