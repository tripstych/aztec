import React, { useState, useRef, useEffect } from "react";
import styled from "styled-components";
import Select from "react-select";

import tw from "twin.macro";
import { Swiper, SwiperSlide } from "swiper/react";
import "swiper/css";
import "swiper/css/navigation";
import { bouncy } from "ldrs";
import { Navigation } from "swiper/modules";
import { useBooking } from "../../hooks/useBooking";
import { CarTypes } from "../../utils/constants";

bouncy.register();

const StepContainer = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    w-full
    py-8
  `}
`;

const CarTypesContainer = styled.div`
  ${tw`
    flex
    gap-4
    flex-wrap
    justify-center
    my-6
  `}
`;

const CarTypeButton = styled.button`
  ${tw`
    text-lg
    md:text-xl
    px-6
    py-2
    rounded-full
    transition-all
    duration-300
    font-semibold
  `}
  display: inline-block;
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(0.9);
  }

  ${({ $selected }) =>
    $selected
      ? `
        background-color: rgba(0, 0, 0, 0.75); 
        color: #1194e4; 
      `
      : tw`bg-transparent text-white hover:text-gray-500`}
`;

const SwiperContainer = styled.div`
  ${tw`
    w-full
    max-w-6xl
    flex
    items-center
    justify-center
    mt-6
  `}

  .swiper-button-next,
  .swiper-button-prev {
    top: 10%;
    background-color: #1194e4;
    color: white;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.1s ease-in-out;

    &:hover {
      transform: scale(0.95);
    }
  }

  .swiper-button-next::after,
  .swiper-button-prev::after {
    font-size: 1.25rem;
  }
`;

const CarImage = styled.img`
  transform: scaleX(-1);
  ${tw`
    pt-6
    w-full
    max-w-xl
    md:max-w-4xl
    h-auto
    object-contain
  `}
`;

const InputContainer = styled.div`
  ${tw`
    flex
    flex-col
    md:flex-row
    gap-4
    md:gap-12
    items-center
  `}
  margin-top: 1rem;
`;

const reactSelectStyles = {
  control: (base, state) => ({
    ...base,
    backgroundColor: "#1194e4",
    opacity: state.isDisabled ? 0.5 : 0.8,
    border: "1px solid #1194e4",
    borderBottom: state.isDisabled ? "1px solid #1194e4" : "2px solid #fff",
    borderRadius: "0.5rem",
    boxShadow: "none",
    padding: "0.25rem",
    fontSize: "1rem",
    fontWeight: 500,
    width: "14rem",
    cursor: "pointer",
    color: "#fff",
    ":hover": {
      borderColor: "#1194e4",
    },
  }),
  option: (base, state) => ({
    ...base,
    backgroundColor: state.isSelected ? "#1194e4" : "#ffffff",
    color: state.isSelected ? "#ffffff" : "#000",
    ":hover": {
      backgroundColor: "#f1f1f1",
    },
    cursor: "pointer",
  }),
  menu: (base) => ({
    ...base,
    borderRadius: "0.5rem",
    overflow: "hidden",
  }),
  singleValue: (base) => ({
    ...base,
    color: "#fff",
  }),
  placeholder: (base) => ({
    ...base,
    color: "#fff",
    fontWeight: 500,
  }),
};

const StepOne = () => {
  const { setBookingData } = useBooking();
  const [selectedType, setSelectedType] = useState(0);
  const [makes, setMakes] = useState([]);
  const [models, setModels] = useState([]);
  const [selectedYear, setSelectedYear] = useState("");
  const [selectedMake, setSelectedMake] = useState("");
  const [selectedModel, setSelectedModel] = useState("");
  const [loading, setLoading] = useState(false);
  const swiperRef = useRef(null);

  const fetchMakes = async (vehicleType) => {
    setLoading(true);
    try {
      const response = await fetch(
        `https://vpic.nhtsa.dot.gov/api/vehicles/GetMakesForVehicleType/${vehicleType}?format=json`
      );
      const data = await response.json();

      // Remove duplicates using a Set
      const uniqueMakes = Array.from(
        new Set(data.Results.map((make) => make.MakeName.trim()))
      );

      setMakes(uniqueMakes);
    } catch (error) {
      console.error("Error fetching makes:", error);
    } finally {
      setLoading(false);
    }
  };

  const fetchModels = async (make, year, vehicleType) => {
    setLoading(true);
    try {
      const response = await fetch(
        `https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/${make}/modelyear/${year}/vehicletype/${vehicleType}?format=json`
      );
      const data = await response.json();
      const uniqueModels = Array.from(
        new Set(data.Results.map((model) => model.Model_Name))
      );
      setModels(uniqueModels);
    } catch (error) {
      console.error("Error fetching models:", error);
    } finally {
      setLoading(false);
    }
  };

  const handleTypeChange = (index) => {
    setSelectedType(index);
    const selectedCarType = CarTypes[index].name;
    const qCarType = CarTypes[index].qName;

    // Reset dependent states
    setSelectedYear("");
    setSelectedMake("");
    setSelectedModel("");
    setMakes([]);
    setModels([]);
    // setYears([]);

    // Update booking data and fetch makes
    setBookingData((prev) => ({
      ...prev,
      carType: selectedCarType,
      year: "",
      make: "",
      model: "",
    }));
    fetchMakes(qCarType);
  };

  const handleYearChange = (selectedOption) => {
    const year = selectedOption?.value || "";
    setSelectedYear(year);
    setSelectedMake("");
    setSelectedModel("");
    setModels([]);
    setBookingData((prev) => ({ ...prev, year }));
  };

  const handleMakeChange = async (selectedOption) => {
    const make = selectedOption?.value || "";
    setSelectedMake(make);
    setSelectedModel("");
    fetchModels(make, selectedYear, CarTypes[selectedType].qName);
    setBookingData((prev) => ({ ...prev, make }));
  };

  const handleModelChange = (selectedOption) => {
    const model = selectedOption?.value || "";
    setSelectedModel(model);
    setBookingData((prev) => ({ ...prev, model }));
  };

  useEffect(() => {
    // Fetch makes for the default car type on initial load
    fetchMakes(CarTypes[selectedType].qName);
    setBookingData((prev) => ({
      ...prev,
      carType: CarTypes[selectedType].name,
      year: "",
      make: "",
      model: "",
    }));
  }, [selectedType, setBookingData]);

  return (
    <StepContainer>
      <CarTypesContainer>
        {CarTypes.map((car, index) => (
          <CarTypeButton
            key={index}
            $selected={index === selectedType}
            onClick={() => {
              handleTypeChange(index);
              if (swiperRef.current) swiperRef.current.slideTo(index);
            }}
          >
            {car.name}
          </CarTypeButton>
        ))}
      </CarTypesContainer>
      <SwiperContainer>
        <Swiper
          spaceBetween={20}
          slidesPerView={1}
          navigation={true}
          modules={[Navigation]}
          onSwiper={(swiper) => (swiperRef.current = swiper)}
          onSlideChange={(swiper) => handleTypeChange(swiper.activeIndex)} // Update car type on slide change
        >
          {CarTypes.map((car, index) => (
            <SwiperSlide
              key={index}
              style={{
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                flexDirection: "column",
              }}
            >
              <CarImage src={car.image} alt={car.name} />
            </SwiperSlide>
          ))}
        </Swiper>
      </SwiperContainer>
      {loading ? (
        <l-bouncy size="55" color="#1194e4" speed="1.75" />
      ) : (
        <InputContainer>
          <Select
            value={
              selectedYear ? { value: selectedYear, label: selectedYear } : null
            }
            onChange={handleYearChange}
            options={Array.from({ length: 25 }, (_, i) => {
              const year = 2023 - i;
              return { value: year, label: year };
            })}
            placeholder="Select Year"
            styles={reactSelectStyles}
            isClearable
          />
          <Select
            value={
              selectedMake ? { value: selectedMake, label: selectedMake } : null
            }
            onChange={handleMakeChange}
            options={makes.map((make) => ({ value: make, label: make }))}
            placeholder="Select Make"
            styles={reactSelectStyles}
            isClearable
            isDisabled={!selectedYear}
          />
          <Select
            value={
              selectedModel
                ? { value: selectedModel, label: selectedModel }
                : null
            }
            onChange={handleModelChange}
            options={models.map((model) => ({ value: model, label: model }))}
            placeholder="Select Model"
            styles={reactSelectStyles}
            isClearable
            isDisabled={!selectedMake}
          />
        </InputContainer>
      )}
    </StepContainer>
  );
};

export default StepOne;
