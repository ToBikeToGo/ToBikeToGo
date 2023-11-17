import Avatar from '@mui/material/Avatar';
import { useTheme } from '@mui/material';

const MemberList = ({ members, onMemberClick }) => {
  const theme = useTheme();

  return (
    <div
      className="member-list"
      style={{
        maxWidth: '100%',
        overflowX: 'auto',
        whiteSpace: 'nowrap',
      }}
    >
      <ul className={'flex p-0 m-0'}>
        {members.map((member) => (
          <button
            style={{
              margin: '0.5em',
              display: 'flex',
              flexDirection: 'row',
              border: '1px solid black',
              borderColor: theme.palette.primary.secondary,
              borderRadius: '12px',
              backgroundColor: theme.palette.primary.main,
            }}
            color="black"
            key={member.id}
            onClick={() => onMemberClick(member)}
          >
            <Avatar
              sx={{
                width: '50px',
                height: '50px',
                marginRight: '0.5em',
              }}
              src={member.profilePicture}
              alt={member.name}
            />
            {member.name}
          </button>
        ))}
      </ul>
    </div>
  );
};

export { MemberList };
