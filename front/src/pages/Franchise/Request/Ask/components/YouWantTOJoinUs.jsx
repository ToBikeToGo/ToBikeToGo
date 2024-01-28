import Typography from '@mui/material/Typography';
import FranchiseImage from '../../../../../assets/images/JoinUs.png';
import { ArrowRight } from '@mui/icons-material';
import Button from '@mui/material/Button';

const YouWantToJoinUs = ({ handleNext }) => {
  return (
    <>
      <Typography
        variant="h2"
        sx={{
          marginBottom: '20px',
        }}
      >
        You want to join us ?
      </Typography>
      <img
        src={FranchiseImage}
        style={{
          width: '50%',
          height: 'auto',
          marginBottom: '20px',
        }}
      />
      <Button
        variant="outlined"
        color="black"
        sx={{
          marginTop: '2em',
        }}
        size={'large'}
        onClick={handleNext}
      >
        Create my Franchise
        <ArrowRight />
      </Button>
    </>
  );
};

export { YouWantToJoinUs };
