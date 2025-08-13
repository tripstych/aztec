import React, { useState } from "react";
import styled from "styled-components";
import tw from "twin.macro";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faClock, faPlus, faCheck } from "@fortawesome/free-solid-svg-icons";
import { useBooking } from "../../hooks/useBooking";
import { GlassTypes } from "../../utils/constants";

const StepContainer = styled.div`
  ${tw`
    flex
    flex-col
    items-center
    w-full
    max-w-5xl
    py-8
  `}
`;

const CardsContainer = styled.div`
  ${tw`
    grid
    grid-cols-1
    md:grid-cols-2
    gap-8
    w-full
    max-w-6xl
    px-8
    cursor-pointer
  `}
`;

const CardWrapper = styled.div`
  ${tw`
    relative
    flex
    items-center
    justify-center
    rounded-lg
    transition-all
    duration-300
  `}
  background: ${({ $isSelected }) =>
    $isSelected ? "linear-gradient(45deg, #32cd32, #00ff00)" : "transparent"};
  padding: 4px;

  &:hover {
    background: ${({ $isSelected }) =>
      $isSelected
        ? "linear-gradient(45deg, #32cd32, #00ff00)"
        : "linear-gradient(45deg, #1194e4, #1e90ff)"};
    border-radius: 10px;
    transition: background 0.5s ease, border-radius 0.5s ease;
  }
`;

const Card = styled.div`
  ${tw`
    bg-white
    rounded-lg
    shadow-lg
    p-10
    flex
    flex-col
    relative
    w-full
  `}
`;

const ContentContainer = styled.div`
  ${tw`
    flex
    items-start
    pb-6
    px-2
  `}
`;

const IconContainer = styled.div`
  ${tw`
    text-4xl
    mr-6
    h-6
  `}
`;

const InfoContainer = styled.div`
  ${tw`
    flex-1
  `}
`;

const Title = styled.h3`
  ${tw`
    text-2xl
    font-bold
    text-black
    mb-2
  `}
`;

const Description = styled.p`
  ${tw`
    text-black
    text-sm
    mb-4
  `}
`;

const DetailsContainer = styled.div`
  ${tw`
    flex
    justify-between
    w-full
    mt-4
    text-lg
    font-bold
    text-black
  `}
`;

const DetailItem = styled.div`
  ${tw`
    flex
    items-center
    gap-2
  `}
`;

const DetailIcon = styled(FontAwesomeIcon)`
  ${tw`
    text-cBlue
    font-bold
    text-lg
  `}
`;

const AddButton = styled.button`
  ${tw`
    absolute
    bottom-[-12px]
    left-1/2
    transform -translate-x-1/2
    text-white
    w-8
    h-8
    rounded-full
    flex
    items-center
    justify-center
    shadow-lg
    transition-all
  `}
  background: ${({ $isSelected }) => ($isSelected ? "#39b972" : "#1194e4")};
`;

const StepTwo = () => {
  const { setBookingData } = useBooking();
  const [selectedIndices, setSelectedIndices] = useState([]);

  const handleCardClick = (index) => {
    const selectedService = GlassTypes[index];
    if (selectedIndices.includes(index)) {
      // Remove from selected
      setSelectedIndices(selectedIndices.filter((i) => i !== index));
      setBookingData((prev) => ({
        ...prev,
        service: prev.service.filter(
          (service) => service.name !== selectedService.title
        ),
      }));
    } else {
      // Add to selected
      setSelectedIndices([...selectedIndices, index]);
      setBookingData((prev) => ({
        ...prev,
        service: [
          ...prev.service,
          { name: selectedService.title, duration: selectedService.time },
        ],
      }));
    }
  };

  return (
    <StepContainer>
      <CardsContainer>
        {GlassTypes.map((glass, index) => (
          <CardWrapper
            key={index}
            $isSelected={selectedIndices.includes(index)}
            onClick={() => handleCardClick(index)}
          >
            <Card>
              <ContentContainer>
                <IconContainer>
                  <img
                    src={glass.icon}
                    alt={`${glass.title} icon`}
                    width="65"
                    height="65"
                  />
                </IconContainer>
                <InfoContainer>
                  <Title>{glass.title}</Title>
                  <Description>{glass.description}</Description>
                  <DetailsContainer>
                    <DetailItem>
                      <DetailIcon icon={faClock} />
                      {glass.time}
                    </DetailItem>
                  </DetailsContainer>
                </InfoContainer>
              </ContentContainer>
              <AddButton $isSelected={selectedIndices.includes(index)}>
                <FontAwesomeIcon
                  icon={selectedIndices.includes(index) ? faCheck : faPlus}
                />
              </AddButton>
            </Card>
          </CardWrapper>
        ))}
      </CardsContainer>
    </StepContainer>
  );
};

export default StepTwo;
