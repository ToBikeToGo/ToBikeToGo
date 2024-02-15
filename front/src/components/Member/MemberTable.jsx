import {
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Avatar,
  IconButton,
} from '@mui/material';
import { Edit, Delete } from '@mui/icons-material';
import { CalendarIcon } from '@mui/x-date-pickers';
import { Link } from 'react-router-dom';
const MemberTable = ({ members, onMemberDelete, onMemberEdit }) => {
  return (
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 650 }} aria-label="simple table">
        <TableHead>
          <TableRow>
            <TableCell>Avatar</TableCell>
            <TableCell>First Name</TableCell>
            <TableCell>Last Name</TableCell>
            <TableCell>Actions</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {members?.map((member) => (
            <TableRow
              key={member.id}
              sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
            >
              <TableCell component="th" scope="row">
                <Avatar alt={member.firstname} src={member.avatar} />
              </TableCell>
              <TableCell>{member.firstname}</TableCell>
              <TableCell>{member.lastname}</TableCell>
              <TableCell>
                <IconButton
                  aria-label="edit"
                  onClick={() => onMemberEdit(member.id)}
                >
                  <Edit />
                </IconButton>
                <IconButton
                  aria-label="delete"
                  onClick={() => onMemberDelete(member.id)}
                >
                  <Delete />
                </IconButton>
                <IconButton
                  aria-label="planning"
                  component={Link}
                  to={`/planning/${member.id}`}
                >
                  <CalendarIcon />
                </IconButton>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
  );
};
export { MemberTable };
