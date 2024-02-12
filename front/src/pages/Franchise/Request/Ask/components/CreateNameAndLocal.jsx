import withToast from '../../../../../components/HOC/WithToastHOC.jsx';
import { ArrowRight } from '@mui/icons-material';
import Button from '@mui/material/Button';
import { TextField } from '@mui/material';
import { useFranchiseRequest } from '../hooks/useFranchiseRequest.jsx';
import Typography from '@mui/material/Typography';

const CreateNameAndLocal = ({ handleNext, setToast }) => {
  const { handleAddLabel, label, checkLabel, error, onSave } =
    useFranchiseRequest();

  const handleSubmit = () => {
    const error = checkLabel(label);
    if (error) {
      console.log('error', error);
      setToast({
        open: true,
        severity: 'error',
        message: error,
      });
      return;
    }
    onSave({
      franchiseLabel: label,
    });
    handleNext();
  };

  return (
    <>
      <Typography
        variant="h3"
        sx={{
          marginBottom: '20px',
        }}
      >
        How do you want to name your franchise ?
      </Typography>
      <TextField
        id="outlined-basic"
        label="Name"
        variant="outlined"
        value={label}
        onChange={(event) => handleAddLabel(event.target.value)}
        sx={{
          marginBottom: '20px',
        }}
        type={'text'}
      />
      <Button
        variant="outlined"
        color="black"
        sx={{
          marginTop: '2em',
        }}
        size={'large'}
        onClick={handleSubmit}
      >
        Next
      </Button>
    </>
  );
};

const CreateNameAndLocalWithToast = withToast(CreateNameAndLocal);
export { CreateNameAndLocalWithToast as CreateNameAndLocal };
