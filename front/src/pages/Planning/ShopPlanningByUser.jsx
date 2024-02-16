import './../../style/planning.css';
import Typography from '@mui/material/Typography';
import { Planning } from '../../components/Planning/Planning.jsx';
import Avatar from '@mui/material/Avatar';
import { addDays } from 'date-fns';
import { MemberList } from '../../components/Planning/MemberList.jsx';
import { useEffect, useState } from 'react';
import { useShop } from '../../hooks/UseShop.jsx';
import { usePlanning } from '../../components/Planning/hooks/usePlanning.jsx';
import { useParams } from 'react-router-dom';
import { CircularProgress } from '@mui/material';

function ShopPlanningByUser() {
  const {
    members,
    name,
    activeMember,
    setActiveMember,
    getShopWithMembers,
    isLoading,
    shop,
  } = useShop();
  const { userId } = useParams();
  const { getWorkingDays, vacations, setUser } = usePlanning({
    fromConnectedUser: false,
  });

  if (isLoading) {
    return <CircularProgress sx={{ m: 5 }} />;
  }
  return (
    <div className={'flex flex-col w-full p-12 '}>
      <Typography variant={'h1'} className={'text-center p-12'}>
        Work at {name}
      </Typography>
      <MemberList members={members} onMemberClick={onMemberClick} />
      <div>
        <div className="flex mx-auto p-2">
          <Avatar
            className={'m-4'}
            src={activeMember?.avatar}
            sx={{
              width: '100px',
              height: '100px',
            }}
          />
          <Typography variant={'h2'}>
            {activeMember.firstname} {activeMember.lastname} Planning
          </Typography>
        </div>

        <Planning events={events} />
      </div>
    </div>
  );
}

export { ShopPlanningByUser };
