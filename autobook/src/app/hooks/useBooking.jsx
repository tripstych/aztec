import React, { createContext, useState, useContext } from "react";

const BookingContext = createContext();

export const useBooking = () => useContext(BookingContext);

export const BookingProvider = ({ children }) => {
  const [bookingData, setBookingData] = useState({
    carType: "",
    year: "",
    make: "",
    model: "",
    selectedDate: "",
    selectedTime: "",
    service: [
      {
        name: "",
        duration: "",
      },
    ],
  });

  return (
    <BookingContext.Provider value={{ bookingData, setBookingData }}>
      {children}
    </BookingContext.Provider>
  );
};
