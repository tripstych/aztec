import React, { useState, useEffect } from "react";
import styled from "styled-components";
import tw from "twin.macro";
import { format, addDays, startOfWeek, isBefore, startOfDay } from "date-fns";
import { useBooking } from "../../hooks/useBooking";

const CalendarContainer = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    w-full
    md:max-w-7xl
    py-8
    px-6
  `}
`;

const Header = styled.div`
  ${tw`
    w-full
    flex
    justify-between
    items-center
    px-10
    py-8
    bg-cBlue
    text-white
    rounded-t-lg
  `}
`;

const Title = styled.h2`
  ${tw`
    text-xl
    md:text-2xl
    font-extrabold
  `}
`;

const WeekRow = styled.div`
  ${tw`
    w-full
    grid
    p-4
    text-center
    font-medium
    rounded-b-lg
    border-b-4
    bg-gray-200
    border-cBlue
    gap-4
  `}
  grid-template-columns: repeat(${({ $daysToShow }) => $daysToShow}, 1fr);
`;

const DayContainer = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    py-4
    px-2
    rounded-md
  `}
`;

const DateText = styled.div`
  ${tw`
    text-xl
    md:text-2xl
    font-extrabold
    text-black
  `}
`;

const WeekText = styled.div`
  ${tw`
    text-sm
    md:text-lg
    font-bold
    text-black
  `}
`;

const TimeSlot = styled.button`
  ${tw`
    text-sm
    text-black
    w-full
    py-2
    px-2
    md:px-4
    mt-1
    rounded-full
    hover:text-white
    transition-all
    duration-200
  `}
  background-color: ${({ $isSelected, $isDisabled }) =>
    $isDisabled ? "#e0e0e0" : $isSelected ? "#39b972" : "#f1f1f1"};
  color: ${({ $isSelected, $isDisabled }) =>
    $isDisabled ? "#a0a0a0" : $isSelected ? "#ffffff" : "#000000"};
  cursor: ${({ $isDisabled }) => ($isDisabled ? "not-allowed" : "pointer")};
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(1.05);
  }
`;

const FooterText = styled.div`
  ${tw`
    text-center
    text-sm
    text-gray-300
    mt-4
  `}
`;

const DateContainer = styled.div`
  ${tw`
    mb-4
  `}
  border-bottom: ${({ $isToday }) => ($isToday ? "2px solid #1194e4" : "none")};
`;

const WeekButton = styled.button`
  ${tw`
    font-bold
    border-2
    rounded-full
    p-2
    px-4
  `}
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(0.95);
  }
`;

const StepThree = () => {
  const { setBookingData } = useBooking();
  const now = new Date(); // Current date and time
  const today = startOfDay(now); // Start of the current day
  const [currentWeek, setCurrentWeek] = useState(
    startOfWeek(today, { weekStartsOn: 0 })
  );
  const [selectedTime, setSelectedTime] = useState({});
  const [daysToShow, setDaysToShow] = useState(7); // Default to showing 7 days

  useEffect(() => {
    const updateDaysToShow = () => {
      if (window.innerWidth <= 768) {
        setDaysToShow(3); // Show 3 days on mobile
      } else {
        setDaysToShow(7); // Show 7 days on larger screens
      }
    };

    updateDaysToShow();
    window.addEventListener("resize", updateDaysToShow);

    return () => window.removeEventListener("resize", updateDaysToShow);
  }, []);

  const daysOfWeek = Array.from({ length: daysToShow }).map((_, i) =>
    addDays(currentWeek, i)
  );

  const timeSlots = Array.from({ length: 8 }, (_, i) => {
    const hour24 = 9 + i; // 9 AM to 5 PM
    const hour12 = hour24 > 12 ? hour24 - 12 : hour24;
    const period = hour24 >= 12 ? "PM" : "AM";
    return `${hour12}:00 ${period}`;
  });

  const handleTimeClick = (date, time) => {
    const [hour, minute] = time
      .replace(/ (AM|PM)/, "")
      .split(":")
      .map(Number);
    const isPM = time.includes("PM");
    const hour24 = isPM && hour < 12 ? hour + 12 : hour;

    const [year, month, day] = date.split("-").map(Number);
    const dateTime = new Date();
    dateTime.setFullYear(year, month - 1, day);
    dateTime.setHours(hour24, minute, 0, 0);

    if (isBefore(dateTime, now)) {
      return;
    }

    setSelectedTime((prev) => ({
      ...prev,
      [date]: prev[date] === time ? null : time,
    }));

    setBookingData((prev) => ({
      ...prev,
      selectedDate: format(new Date(date), "MMMM dd, yyyy"),
      selectedTime: time,
    }));
  };

  const goToNextWeek = () => {
    setCurrentWeek(addDays(currentWeek, daysToShow));
  };

  const goToPreviousWeek = () => {
    const previousWeekStart = addDays(currentWeek, -daysToShow);
    if (!isBefore(previousWeekStart, today)) {
      setCurrentWeek(previousWeekStart);
    } else {
      setCurrentWeek(startOfWeek(today, { weekStartsOn: 0 }));
    }
  };

  return (
    <CalendarContainer>
      <Header>
        <WeekButton onClick={goToPreviousWeek}>&lt;</WeekButton>
        <Title>{format(currentWeek, "MMMM yyyy")}</Title>
        <WeekButton onClick={goToNextWeek}>&gt;</WeekButton>
      </Header>

      <WeekRow $daysToShow={daysToShow}>
        {daysOfWeek.map((date) => (
          <DayContainer key={format(date, "yyyy-MM-dd")}>
            <DateContainer
              $isToday={
                format(date, "yyyy-MM-dd") === format(today, "yyyy-MM-dd")
              }
            >
              <DateText>{format(date, "dd")}</DateText>
              <WeekText>{format(date, "EEEE")}</WeekText>
            </DateContainer>

            <div className="grid grid-cols-1 gap-2 mt-2">
              {timeSlots.map((time) => {
                const [hour, minute] = time
                  .replace(/ (AM|PM)/, "")
                  .split(":")
                  .map(Number);
                const isPM = time.includes("PM");
                const hour24 = isPM && hour < 12 ? hour + 12 : hour;

                const [year, month, day] = format(date, "yyyy-MM-dd")
                  .split("-")
                  .map(Number);
                const dateTime = new Date();
                dateTime.setFullYear(year, month - 1, day);
                dateTime.setHours(hour24, minute, 0, 0);

                const isDisabled =
                  isBefore(new Date(date), today) ||
                  (format(date, "yyyy-MM-dd") === format(today, "yyyy-MM-dd") &&
                    isBefore(dateTime, now));

                return (
                  <TimeSlot
                    key={`${format(date, "yyyy-MM-dd")}-${time}`}
                    onClick={() =>
                      handleTimeClick(format(date, "yyyy-MM-dd"), time)
                    }
                    $isSelected={
                      selectedTime[format(date, "yyyy-MM-dd")] === time
                    }
                    $isDisabled={isDisabled}
                  >
                    {time}
                  </TimeSlot>
                );
              })}
            </div>
          </DayContainer>
        ))}
      </WeekRow>

      <FooterText>
        <span className="text-red-600 font-bold">*</span> Reservation times are
        approximate and may vary within 60 minutes.
      </FooterText>
    </CalendarContainer>
  );
};

export default StepThree;
