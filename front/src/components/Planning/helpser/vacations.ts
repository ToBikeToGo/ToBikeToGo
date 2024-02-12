import { VACATIONS_REQUEST_STATUS } from '../../../pages/Planning/constants/vacations';

export const isVacationApproved = (vacation) => {
  return vacation?.status === true;
};
